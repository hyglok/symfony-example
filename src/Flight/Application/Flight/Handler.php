<?php
declare(strict_types=1);

namespace Flight\Application\Flight;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Flight\Flight;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class Handler implements MessageSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Register $command
     */
    function register(Register $command)
    {
        $this->entityManager->persist(Flight::register());
    }

    public static function getHandledMessages(): iterable
    {
        yield Register::class => [
            'method' => 'register',
            'bus' => 'command',
        ];
    }
}