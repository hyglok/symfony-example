<?php
declare(strict_types=1);

namespace Flight\Model\Reservation\Event;

use Lib\Model\Event;

final class Paid implements Event
{
    public int $seat;
    public string $reservationId;
    public string $customerId;
    public string $flightId;
    public string $firstName;
    public string $lastName;
    public string $email;

    public function __construct(
        string $reservationId,
        int $seat,
        string $customerId,
        string $flightId,
        string $firstName,
        string $lastName,
        string $email
    )
    {
        $this->reservationId = $reservationId;
        $this->flightId = $flightId;
        $this->seat = $seat;
        $this->customerId = $customerId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
}