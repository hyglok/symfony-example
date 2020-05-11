<?php

namespace Flight\Application\Ticket\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Application\Ticket\Cancel;
use Flight\Model\Flight\Event\Cancelled;
use Flight\Model\Ticket\Ticket;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FlightCancelledListener implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $commandBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $commandBus)
    {
        $this->entityManager = $entityManager;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Cancelled $event)
    {
        $ticketsIds = $this->entityManager->getRepository(Ticket::class)->findPurchasedTicketsIds($event->id);
        foreach ($ticketsIds as $id) {
            $this->commandBus->dispatch(new Cancel($id));
        }
    }
}