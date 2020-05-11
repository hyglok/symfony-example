<?php
declare(strict_types=1);

namespace Flight\Application\Ticket;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Flight\Flight;
use Flight\Model\Ticket\Passenger;
use Flight\Model\Ticket\Ticket;
use Flight\Repository\FlightRepository;
use Lib\Model\Email;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class Handler implements MessageSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Handler constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    function purchase(Purchase $command)
    {
        $flight = $this->entityManager->find(Flight::class, $command->flightId);
        if (!$flight) {
            throw new \InvalidArgumentException("Flight with id $command->flightId not exists");
        }
        $ticket = $this->entityManager->getRepository(Ticket::class)->findOneBySeat($command->seat, $command->flightId);
        if ($ticket) {
            throw new \InvalidArgumentException("The seat $command->seat have already been occupied");
        }

        $ticket = Ticket::purchase(
            $command->seat,
            $command->customerId,
            $flight,
            new Passenger($command->firstName, $command->lastName, new Email($command->email))
        );
        $this->entityManager->persist($ticket);
    }

    function refund(Refund $command)
    {
        $ticket = $this->entityManager->find(Ticket::class, $command->ticketId);
        if (!$ticket) {
            throw new \InvalidArgumentException("Ticket with id $command->ticketId not exists");
        }
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