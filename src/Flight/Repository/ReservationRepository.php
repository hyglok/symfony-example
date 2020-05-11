<?php

namespace Flight\Repository;

use Doctrine\ORM\EntityRepository;
use Flight\Model\Reservation\Reservation;

class ReservationRepository extends EntityRepository
{
    /**
     * @param $seat
     * @param $flightId
     *
     * @return Reservation|null
     */
    public function findOneBySeat($seat, $flightId): ?Reservation
    {
        /** @var Reservation|null $reservation */
        $reservation = $this->findOneBy(['flight' => $flightId, 'seat' => $seat]);

        return $reservation;
    }
}