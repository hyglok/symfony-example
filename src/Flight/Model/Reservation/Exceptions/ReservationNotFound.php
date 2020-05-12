<?php
declare(strict_types=1);

namespace Flight\Model\Reservation\Exceptions;

class ReservationNotFound extends \Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Reservation with id $id not exists");
    }
}