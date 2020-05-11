<?php
declare(strict_types=1);

namespace Flight\Application\Ticket;

use Symfony\Component\Validator\Constraints as Assert;

class Refund
{
    /**
     * @Assert\Uuid
     */
    public string $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }
}