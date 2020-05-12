<?php
declare(strict_types=1);

namespace Flight\Application;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Model\Reservation\Reservation;
use Flight\Model\Ticket\Ticket;

class SeatChecker
{
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

    public function isSeatAvailable(string $flightId, int $seat): bool
    {
        $ticket = $this->entityManager->getRepository(Ticket::class)->findOneBySeat($seat, $flightId);
        $reservation = $this->entityManager->getRepository(Reservation::class)->findOneBySeat($seat, $flightId);

        if ($ticket || $reservation) {
            return false;
        }

        return true;
    }
}