<?php

namespace Flight\Repository;

use Doctrine\ORM\EntityRepository;
use Flight\Model\Ticket\Status;
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

    /**
     * @param $flightId
     *
     * @return array [string]
     */
    public function findPurchasedTicketsIds($flightId): array
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.id')
            ->where('t.flightId = :flightId')
            ->andWhere('t.status.status = :status')
            ->setParameter('flightId', $flightId)
            ->setParameter('status', Status::PURCHASED)
            ->getQuery()
            ->getResult();

        return array_map('current', $result);
    }
}