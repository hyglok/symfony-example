<?php
declare(strict_types=1);

namespace Shared\Event\Ticket;

use Shared\Model\Event;

final class Refunded implements Event
{
    public string $id;
    public string $flightId;

    public function __construct(string $id, string $flightId)
    {
        $this->id = $id;
        $this->flightId = $flightId;
    }
}