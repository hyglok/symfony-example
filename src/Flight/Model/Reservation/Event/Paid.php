<?php
declare(strict_types=1);

namespace Flight\Model\Reservation\Event;

use Flight\Model\Passenger;
use Lib\Model\Event;

final class Paid implements Event
{
    public int $seat;
    public string $reservationId;
    public string $customerId;
    public string $flightId;
    public Passenger $passenger;

    public function __construct(
        string $reservationId,
        int $seat,
        string $customerId,
        string $flightId,
        Passenger $passenger
    )
    {
        $this->reservationId = $reservationId;
        $this->flightId = $flightId;
        $this->seat = $seat;
        $this->customerId = $customerId;
        $this->passenger = $passenger;
    }
}