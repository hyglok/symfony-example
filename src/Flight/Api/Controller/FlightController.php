<?php
declare(strict_types=1);

namespace Flight\Api\Controller;

use Flight\Api\DTO\FlightEvent;
use Flight\Application\Flight\Cancel;
use Flight\Application\Flight\CloseSale;
use Flight\Application\Flight\QueryObject\FlightsQO;
use Flight\Application\Flight\Register;
use Lib\HttpFoundation\Fail;
use Lib\HttpFoundation\Result;
use Lib\HttpFoundation\Success;
use Lib\QueryObject\Listing;
use Lib\QueryObject\QueryExecutor;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/flights")
 */
class FlightController
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
     * @Route("/register", methods={"POST"})
     * @param Register $command
     *
     * @return Result
     */
    public function register(Register $command): Result
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            return Fail::fromValidation($errors);
        }
        $this->commandBus->dispatch($command);

        return Success::ok();
    }

    /**
     * @Route("/events", methods={"POST"})
     * @param FlightEvent $event
     *
     * @return Result
     */
    public function events(FlightEvent $event): Result
    {
        $errors = $this->validator->validate($event);
        if (count($errors) > 0) {
            return Fail::fromValidation($errors);
        }

        if ($event->secretKey != $_ENV['FLIGHTS_CALLBACK_SECRET']) {
            throw new UnauthorizedHttpException("Invalid secret key");
        }

        if ($event->isSalesCompleted()) {
            $command = new CloseSale($event->flightId);
        } elseif ($event->isFlightCancelled()) {
            $command = new Cancel($event->flightId);
        } else {
            throw new \InvalidArgumentException("Unknown event type");
        }

        $this->commandBus->dispatch($command);

        return Success::ok();
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @return Result
     */
    public function flights(): Result
    {
        return new Success(
            new Listing($this->queryExecutor->execute(new FlightsQO()))
        );
    }
}