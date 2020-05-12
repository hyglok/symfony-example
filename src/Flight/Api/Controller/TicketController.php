<?php
declare(strict_types=1);

namespace Flight\Api\Controller;

use Flight\Application\Ticket\Purchase;
use Flight\Application\Ticket\QueryObject\TicketsQO;
use Flight\Application\Ticket\Refund;
use Lib\HttpFoundation\Fail;
use Lib\HttpFoundation\Result;
use Lib\HttpFoundation\Success;
use Lib\QueryObject\Listing;
use Lib\QueryObject\QueryExecutor;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/tickets")
 */
class TicketController
{
    private MessageBusInterface $commandBus;
    private ValidatorInterface $validator;
    private LockFactory $lockFactory;
    private QueryExecutor $queryExecutor;

    public function __construct(
        MessageBusInterface $commandBus,
        ValidatorInterface $validator,
        LockFactory $lockFactory,
        QueryExecutor $queryExecutor
    )
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->lockFactory = $lockFactory;
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @Route("/purchase", methods={"POST"})
     * @param Purchase $command
     *
     * @return Result
     */
    public function purchase(Purchase $command): Result
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            return Fail::fromValidation($errors);
        }

        $lock = $this->lockFactory->createLock($command->flightId . $command->seat);
        if ($lock->acquire()) {
            $this->commandBus->dispatch($command);
            $lock->release();
        }

        return Success::ok();
    }

    /**
     * @Route("/refund/{ticketId}", methods={"POST"})
     * @param string $ticketId
     *
     * @return Result
     */
    public function refund(string $ticketId): Result
    {
        $command = new Refund($ticketId);
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            return Fail::fromValidation($errors);
        }

        $lock = $this->lockFactory->createLock('refund' . $ticketId);
        if ($lock->acquire()) {
            $this->commandBus->dispatch($command);
            $lock->release();
        }

        return Success::ok();
    }

    /**
     * @Route("/{flightId}", methods={"GET"})
     * @param string $flightId
     *
     * @return Result
     */
    public function tickets(string $flightId): Result
    {
        return new Success(
            new Listing($this->queryExecutor->execute(new TicketsQO($flightId)))
        );
    }
}
