<?php
declare(strict_types=1);

namespace Flight\Application\Ticket\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Flight\Application\Ticket\Purchase;
use Flight\Model\Reservation\Event\Paid;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ReservationPaidListener implements MessageHandlerInterface
{
    private MessageBusInterface $commandBus;
    private LockFactory $lockFactory;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $commandBus, LockFactory $lockFactory)
    {
        $this->commandBus = $commandBus;
        $this->lockFactory = $lockFactory;
        $this->em = $em;
    }

    public function __invoke(Paid $event)
    {
        $command = new Purchase();
        $command->seat = $event->seat;
        $command->customerId = $event->customerId;
        $command->flightId = $event->flightId;
        $command->firstName = $event->passenger->firstName();
        $command->lastName = $event->passenger->lastName();
        $command->email = $event->passenger->email()->address();
        $command->fromReservation = true;

        $lock = $this->lockFactory->createLock($command->flightId . $command->seat);
        if ($lock->acquire()) {
            $this->commandBus->dispatch($command);
//            $this->em->flush();
            $lock->release();
        }
    }
}