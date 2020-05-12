<?php
declare(strict_types=1);

namespace Flight\Model\Flight\Exceptions;

class FlightNotFound extends \Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Flight with id $id not exists");
    }
}