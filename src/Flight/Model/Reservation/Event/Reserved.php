<?php
declare(strict_types=1);

namespace Flight\Model\Reservation\Event;

use Lib\Model\Event;

final class Reserved implements Event
{
    public string $id;
    public string $flightId;

    public function __construct(string $id, string $flightId)
    {
        $this->id = $id;
        $this->flightId = $flightId;
    }
}