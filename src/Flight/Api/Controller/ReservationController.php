<?php
declare(strict_types=1);

namespace Flight\Api\Controller;

use Flight\Application\Reservation\Cancel;
use Flight\Application\Reservation\Pay;
use Flight\Application\Reservation\Reserve;
use Lib\HttpFoundation\Fail;
use Lib\HttpFoundation\Result;
use Lib\HttpFoundation\Success;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/reservations")
 */
class ReservationController
{
    private MessageBusInterface $commandBus;
    private ValidatorInterface $validator;
    private LockFactory $lockFactory;

    public function __construct(
        MessageBusInterface $commandBus,
        ValidatorInterface $validator,
        LockFactory $lockFactory
    )
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->lockFactory = $lockFactory;
    }

    /**
     * @Route("/reserve", methods={"POST"})
     * @param Reserve $command
     *
     * @return Result
     */
    public function reserve(Reserve $command): Result
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
     * @Route("/cancel/{reservationId}", methods={"POST"})
     * @param string $reservationId
     *
     * @return Result
     */
    public function cancel(string $reservationId): Result
    {
        $command = new Cancel($reservationId);
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            return Fail::fromValidation($errors);
        }
        $this->commandBus->dispatch($command);

        return Success::ok();
    }

    /**
     * @Route("/pay/{reservationId}", methods={"POST"})
     * @param string $reservationId
     *
     * @return Result
     */
    public function pay(string $reservationId): Result
    {
        $command = new Pay($reservationId);
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            return Fail::fromValidation($errors);
        }
        $this->commandBus->dispatch($command);

        return Success::ok();
    }
}