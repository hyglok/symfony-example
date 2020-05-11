<?php
declare(strict_types=1);

namespace Payment\Application\Ticket;

use Flight\Model\Ticket\Event\Refunded;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TicketRefundedSubscriber implements MessageHandlerInterface
{
    public function __invoke(Refunded $event)
    {
        echo "Ticket $event->id successfully refunded";
    }
}