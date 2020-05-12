<?php
declare(strict_types=1);

namespace Flight\Model\Ticket\Exceptions;

class TicketNotFound extends \Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Ticket with id $id not exists");
    }
}