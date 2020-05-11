<?php

namespace Flight\Repository;

use Doctrine\ORM\EntityRepository;
use Flight\Model\Ticket\Ticket;

class TicketRepository extends EntityRepository
{
    /**
     * @param $seat
     * @param $flightId
     *
     * @return Ticket|null
     */
    public function findOneBySeat($seat, $flightId): ?Ticket
    {
        /** @var Ticket|null $ticket */
        $ticket = $this->findOneBy(['flightId' => $flightId, 'seat' => $seat]);

        return $ticket;
    }
}