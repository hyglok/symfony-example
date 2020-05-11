<?php
declare(strict_types=1);

namespace Flight\Api\Controller;

use Flight\Application\Ticket\Purchase;
use Flight\Application\Ticket\Refund;
use Shared\HttpFoundation\Result;
use Shared\HttpFoundation\Success;
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

    public function __construct(MessageBusInterface $commandBus, ValidatorInterface $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
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
            var_dump((string) $errors);exit;
        }
        $this->commandBus->dispatch($command);

        return new Success($command);
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
            var_dump((string) $errors);exit;
        }
        $this->commandBus->dispatch($command);

        return new Success($command);
    }
}
