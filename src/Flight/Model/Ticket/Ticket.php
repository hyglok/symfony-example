<?php
declare(strict_types=1);

namespace Flight\Model\Ticket;

use Doctrine\ORM\Mapping as ORM;
use Flight\Model\Flight\Flight;
use Flight\Model\Ticket\Event\Purchased;
use Flight\Model\Ticket\Event\Refunded;
use Lib\Model\AggregateRoot;

/**
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="Flight\Repository\TicketRepository")
 */
class Ticket extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $seat;

    /**
     * @ORM\Column(type="guid")
     */
    private string $customerId;

    /**
     * @ORM\ManyToOne(targetEntity="Flight\Model\Flight\Flight")
     * @ORM\JoinColumn(name="flight_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private Flight $flight;

    /**
     * @ORM\Embedded(class="Passenger")
     */
    private Passenger $passenger;

    /**
     * @ORM\Embedded(class="Status", columnPrefix=false)
     */
    private Status $status;

    /**
     * @param int $seat
     * @param string $customerId
     * @param Flight $flight
     * @param Passenger $passenger
     */
    private function __construct(int $seat, string $customerId, Flight $flight, Passenger $passenger)
    {
        $this->id = uuid_create();
        $this->status = Status::purchase();
        $this->seat = $seat;
        $this->passenger = $passenger;
        $this->customerId = $customerId;
        $this->flight = $flight;
    }

    /**
     * @param int $seat
     * @param string $customerId
     * @param Flight $flight
     * @param Passenger $passenger
     *
     * @return static
     */
    public static function purchase(int $seat, string $customerId, Flight $flight, Passenger $passenger): self
    {
        $ticket = new self($seat, $customerId, $flight, $passenger);
        $ticket->addEvent(new Purchased($ticket->id, $ticket->flight->getId()));

        return $ticket;
    }

    public function refund()
    {
        if(!$this->flight->isRefundPossible()) {
            throw new \LogicException("Refund for this flight is closed");
        }
        $this->status->refund();
        $this->addEvent(new Refunded($this->id, $this->flight->getId()));
    }
}