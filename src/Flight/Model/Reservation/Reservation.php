<?php
declare(strict_types=1);

namespace Flight\Model\Reservation;

use Flight\Model\Flight\Flight;
use Flight\Model\Passenger;
use Flight\Model\Reservation\Event\Cancelled;
use Flight\Model\Reservation\Event\Paid;
use Flight\Model\Reservation\Event\Reserved;
use Lib\Model\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="reservations")
 * @ORM\Entity(repositoryClass="Flight\Repository\ReservationRepository")
 */
class Reservation extends AggregateRoot
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
     * @ORM\Column(type="guid", name="ticket_id", nullable=true)
     */
    private string $ticketId;

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
        $this->status = Status::reserve();
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
    public static function reserve(int $seat, string $customerId, string $flightId, Passenger $passenger): self
    {
        $reservation = new self($seat, $customerId, $flightId, $passenger);
        $reservation->addEvent(new Reserved($reservation->id, $flightId));

        return $reservation;
    }

    public function cancel()
    {
        $this->status->cancel();
        $this->addEvent(new Cancelled($this->id, $this->flightId));
    }

    public function pay()
    {
        $this->status->pay();
        $this->addEvent(new Paid(
            $this->id,
            $this->seat,
            $this->customerId,
            $this->flightId,
            $this->passenger->firstName(),
            $this->passenger->lastName(),
            $this->passenger->email()->address()
        ));
    }

    /**
     * @return string
     */
    public function flight(): string
    {
        return $this->flightId;
    }
}