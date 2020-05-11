<?php
declare(strict_types=1);

namespace Flight\Api\Controller;

use Symfony\Component\Messenger\MessageBusInterface;

final class ReservationController
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}