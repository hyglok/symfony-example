<?php
declare(strict_types=1);

namespace Identity\Model\Event;

use Lib\Model\Event;

final class Registered implements Event
{
    public string $id;
    public string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
