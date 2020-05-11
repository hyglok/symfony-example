<?php
declare(strict_types=1);

namespace Lib\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use Lib\Model\AggregateRoot;
use Lib\Model\Event;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class DoctrineMiddleware implements MiddlewareInterface
{
    /**
     * @var ManagerRegistry
     */
    protected ManagerRegistry $managerRegistry;
    /**
     * @var string|null
     */
    protected ?string $entityManagerName;
    /**
     * @var TransportInterface
     */
    private TransportInterface $transport;

    /**
     * DoctrineMiddleware constructor.
     *
     * @param TransportInterface $transport
     * @param ManagerRegistry $managerRegistry
     * @param string|null $entityManagerName
     */
    public function __construct(TransportInterface $transport, ManagerRegistry $managerRegistry, $entityManagerName = null)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManagerName = $entityManagerName;
        $this->transport = $transport;
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     *
     * @return Envelope
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    final public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->managerRegistry->getManager($this->entityManagerName);
        } catch (\InvalidArgumentException $e) {
            throw new UnrecoverableMessageHandlingException($e->getMessage(), 0, $e);
        }

        $entityManager->getConnection()->beginTransaction();
        try {
            $envelope = $stack->next()->handle($envelope, $stack);
            $this->fireEvents($entityManager->getUnitOfWork());
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            $entityManager->clear();

            return $envelope;
        } catch (\Throwable $exception) {
            $entityManager->getConnection()->rollBack();

            if ($exception instanceof HandlerFailedException) {
                // Remove all HandledStamp from the envelope so the retry will execute all handlers again.
                // When a handler fails, the queries of allegedly successful previous handlers just got rolled back.
                throw new HandlerFailedException($exception->getEnvelope()->withoutAll(HandledStamp::class), $exception->getNestedExceptions());
            }

            throw $exception;
        }

    }

    /**
     * @param UnitOfWork $unitOfWork
     */
    private function fireEvents(UnitOfWork $unitOfWork)
    {
        array_map(
            function ($entities) {
                array_map(
                    function ($entity) {
                        if ($entity instanceof AggregateRoot) {
                            array_map(
                                function (Event $event) {
                                    $this->transport->send(Envelope::wrap($event));
                                },
                                $entity->riseEvents()
                            );
                        }
                    },
                    $entities
                );
            },
            $unitOfWork->getIdentityMap()
        );
    }
}
