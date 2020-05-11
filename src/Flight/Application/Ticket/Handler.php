<?php
declare(strict_types=1);

namespace Flight\Application\Ticket;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Application\SeatChecker;
use Flight\Model\Flight\Flight;
use Flight\Model\Passenger;
use Flight\Model\Ticket\Ticket;
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

    function purchase(Purchase $command)
    {
        $flight = $this->entityManager->find(Flight::class, $command->flightId);
        if (!$flight) throw new \InvalidArgumentException("Flight with id $command->flightId not exists");

        if (!$command->fromReservation && !$this->seatChecker->isSeatAvailable($command->flightId, $command->seat)) {
            throw new \InvalidArgumentException("The seat $command->seat have already been occupied");
        }

        $ticket = Ticket::purchase(
            $command->seat,
            $command->customerId,
            $flight->getId(),
            new Passenger($command->firstName, $command->lastName, new Email($command->email))
        );
        $this->entityManager->persist($ticket);
    }

    function refund(Refund $command)
    {
        $ticket = $this->entityManager->find(Ticket::class, $command->ticketId);
        if (!$ticket) throw new \InvalidArgumentException("Ticket with id $command->ticketId not exists");

        $flight = $this->entityManager->find(Flight::class, $ticket->flight());
        if(!$flight->isRefundAvailable()) throw new \LogicException(sprintf("Refund for %s is closed", $flight->getId()));

        $ticket->refund();
    }

    public static function getHandledMessages(): iterable
    {
        yield Purchase::class => [
            'method' => 'purchase',
            'bus' => 'command',
        ];
        yield Refund::class => [
            'method' => 'refund',
            'bus' => 'command',
        ];
    }
}