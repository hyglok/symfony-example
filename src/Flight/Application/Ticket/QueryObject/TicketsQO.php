<?php
declare(strict_types=1);

namespace Flight\Application\Ticket\QueryObject;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Ticket\Ticket;
use Lib\QueryObject\QueryObject;

class TicketsQO  implements QueryObject
{
    private string $flightId;

    public function __construct(string $flightId)
    {
        $this->flightId = $flightId;
    }

    /**
     * @param EntityManagerInterface $manager
     *
     * @return Ticket[]
     */
    public function getData(EntityManagerInterface $manager): array
    {
        return $manager->createQueryBuilder()
            ->select([
                't.id',
                't.seat',
                't.status.status as status',
                't.customerId',
                't.passenger.firstName as passengerFirstName',
                't.passenger.lastName as passengerLastName',
                't.passenger.email.address as passengerEmail',
            ])
            ->from(Ticket::class, 't')
            ->where("t.flightId = :flightId")
            ->setParameter('flightId', $this->flightId)
            ->getQuery()
            ->getResult();
    }
}