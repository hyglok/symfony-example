<?php
declare(strict_types=1);

namespace Lib\Model;

abstract class AggregateRoot
{
    /** @var array []Event */
    private array $events = [];

    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    public function riseEvents(): array
    {
        return $this->events;
    }
}
