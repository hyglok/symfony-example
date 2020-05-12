<?php
declare(strict_types=1);

namespace Flight\Application\Flight;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Flight\Exceptions\FlightNotFound;
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

    /**
     * @param CloseSale $command
     *
     * @throws FlightNotFound
     */
    function closeSale(CloseSale $command)
    {
        $flight = $this->entityManager->find(Flight::class, $command->flightId);
        if (!$flight) throw new FlightNotFound($command->flightId);

        $flight->closeSale();
    }

    /**
     * @param Cancel $command
     *
     * @throws FlightNotFound
     */
    function cancel(Cancel $command)
    {
        $flight = $this->entityManager->find(Flight::class, $command->flightId);
        if (!$flight) throw new FlightNotFound($command->flightId);

        $flight->cancel();
    }

    public static function getHandledMessages(): iterable
    {
        yield Register::class => [
            'method' => 'register',
            'bus' => 'command',
        ];
        yield CloseSale::class => [
            'method' => 'closeSale',
            'bus' => 'command',
        ];
        yield Cancel::class => [
            'method' => 'cancel',
            'bus' => 'command',
        ];
    }
}