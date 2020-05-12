<?php
declare(strict_types=1);

namespace Flight\Model\Flight\Exceptions;

class SeatOccupied extends \Exception
{
    public function __construct(int $seat)
    {
        parent::__construct("The seat $seat have already been occupied");
    }
}