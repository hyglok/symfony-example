<?php
declare(strict_types=1);

namespace Flight\Model\Flight\Event;

use Lib\Model\Event;

final class Registered implements Event
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}