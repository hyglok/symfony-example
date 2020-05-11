<?php
declare(strict_types=1);

namespace Flight\Model\Ticket;

use Doctrine\ORM\Mapping as ORM;
use Flight\Model\Passenger;
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
     * @ORM\Column(type="guid")
     */
    private string $flightId;

    /**
     * @ORM\Embedded(class="Flight\Model\Passenger")
     */
    private Passenger $passenger;

    /**
     * @ORM\Embedded(class="Status", columnPrefix=false)
     */
    private Status $status;

    /**
     * @param int $seat
     * @param string $customerId
     * @param string $flightId
     * @param Passenger $passenger
     */
    private function __construct(int $seat, string $customerId, string $flightId, Passenger $passenger)
    {
        $this->id = uuid_create();
        $this->status = Status::purchase();
        $this->seat = $seat;
        $this->passenger = $passenger;
        $this->customerId = $customerId;
        $this->flightId = $flightId;
    }

    /**
     * @param int $seat
     * @param string $customerId
     * @param string $flightId
     * @param Passenger $passenger
     *
     * @return static
     */
    public static function purchase(int $seat, string $customerId, string $flightId, Passenger $passenger): self
    {
        $ticket = new self($seat, $customerId, $flightId, $passenger);
        $ticket->addEvent(new Purchased($ticket->id, $ticket->flightId));

        return $ticket;
    }

    public function refund()
    {
        $this->status->refund();
        $this->addEvent(new Refunded($this->id, $this->flightId));
    }

    /**
     * @return string
     */
    public function flight(): string
    {
        return $this->flightId;
    }
}