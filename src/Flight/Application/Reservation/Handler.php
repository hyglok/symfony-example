<?php
declare(strict_types=1);

namespace Flight\Application\Reservation;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Application\SeatChecker;
use Flight\Model\Flight\Exceptions\FlightNotFound;
use Flight\Model\Flight\Exceptions\SeatOccupied;
use Flight\Model\Flight\Flight;
use Flight\Model\Passenger;
use Flight\Model\Reservation\Exceptions\ReservationNotFound;
use Flight\Model\Reservation\Reservation;
use Lib\Model\Email;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class Handler implements MessageSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private SeatChecker $seatChecker;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SeatChecker $seatChecker
     */
    public function __construct(EntityManagerInterface $entityManager, SeatChecker $seatChecker)
    {
        $this->entityManager = $entityManager;
        $this->seatChecker = $seatChecker;
    }

    /**
     * @param Reserve $command
     *
     * @throws FlightNotFound | SeatOccupied
     */
    function reserve(Reserve $command)
    {
        $flight = $this->entityManager->find(Flight::class, $command->flightId);
        if (!$flight) throw new FlightNotFound($command->flightId);
        if(!$flight->isTicketsSaleOpened()) throw new \LogicException("Tickets sale for this flight is closed");

        if (!$this->seatChecker->isSeatAvailable($command->flightId, $command->seat)) {
            throw new SeatOccupied($command->seat);
        }

        $reservation = Reservation::reserve(
            $command->seat,
            $command->customerId,
            $flight->getId(),
            new Passenger($command->firstName, $command->lastName, new Email($command->email))
        );
        $this->entityManager->persist($reservation);
    }

    /**
     * @param Cancel $command
     *
     * @throws ReservationNotFound
     */
    function cancel(Cancel $command)
    {
        $reservation = $this->entityManager->find(Reservation::class, $command->reservationId);
        if (!$reservation) throw new ReservationNotFound($command->reservationId);

        $reservation->cancel();
    }

    /**
     * @param Pay $command
     *
     * @throws FlightNotFound | ReservationNotFound
     */
    function pay(Pay $command)
    {
        $reservation = $this->entityManager->find(Reservation::class, $command->reservationId);
        if (!$reservation) throw new ReservationNotFound($command->reservationId);

        $flight = $this->entityManager->find(Flight::class, $reservation->flight());
        if (!$flight) throw new FlightNotFound($reservation->flight());
        if(!$flight->isTicketsSaleOpened()) throw new \LogicException("Tickets sale for this flight is closed");

        $reservation->pay();
    }

    public static function getHandledMessages(): iterable
    {
        yield Reserve::class => [
            'method' => 'reserve',
            'bus' => 'command',
        ];
        yield Cancel::class => [
            'method' => 'cancel',
            'bus' => 'command',
        ];
        yield Pay::class => [
            'method' => 'pay',
            'bus' => 'command',
        ];
    }
}