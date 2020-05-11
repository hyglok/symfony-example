<?php
declare(strict_types=1);

namespace Flight\Application\Reservation;

use Symfony\Component\Validator\Constraints as Assert;

class Pay
{
    /**
     * @Assert\Uuid
     */
    public string $reservationId;

    public function __construct(string $reservationId)
    {
        $this->reservationId = $reservationId;
    }
}