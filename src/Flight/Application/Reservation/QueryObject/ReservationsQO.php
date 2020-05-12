<?php
declare(strict_types=1);

namespace Flight\Application\Reservation\QueryObject;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Reservation\Reservation;
use Lib\QueryObject\QueryObject;

class ReservationsQO implements QueryObject
{
    private string $flightId;

    public function __construct(string $flightId)
    {
        $this->flightId = $flightId;
    }

    /**
     * @param EntityManagerInterface $manager
     *
     * @return Reservation[]
     */
    public function getData(EntityManagerInterface $manager): array
    {
        return $manager->createQueryBuilder()
            ->select([
                'r.id',
                'r.seat',
                'r.status.status as status',
                'r.customerId',
                'r.ticketId',
                'r.passenger.firstName as passengerFirstName',
                'r.passenger.lastName as passengerLastName',
                'r.passenger.email.address as passengerEmail',
            ])
            ->from(Reservation::class, 'r')
            ->where("r.flightId = :flightId")
            ->setParameter('flightId', $this->flightId)
            ->getQuery()
            ->getResult();
    }
}