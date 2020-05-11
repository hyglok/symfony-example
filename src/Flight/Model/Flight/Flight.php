<?php
declare(strict_types=1);

namespace Flight\Model\Flight;

use Doctrine\ORM\Mapping as ORM;
use Flight\Model\Flight\Event\Cancelled;
use Flight\Model\Flight\Event\Registered;
use Flight\Model\Flight\Event\SaleClosed;
use Lib\Model\AggregateRoot;

/**
 * @ORM\Table(name="flights")
 * @ORM\Entity(repositoryClass="Flight\Repository\FlightRepository")
 */
class Flight extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * @ORM\Embedded(class="Status", columnPrefix=false)
     */
    private Status $status;

    //TODO: Flight info fields

    private function __construct()
    {
        $this->id = uuid_create();
        $this->status = Status::openSale();
    }

    /**
     * @return static
     */
    public static function register(): self
    {
        $flight = new self();
        $flight->addEvent(new Registered($flight->id));
        return $flight;
    }

    public function closeSale()
    {
        $this->status->closeSale();
        $this->addEvent(new SaleClosed($this->id));
    }

    public function cancel()
    {
        $this->status->cancel();
        $this->addEvent(new Cancelled($this->id));
    }

    /**
     * @return bool
     */
    public function isRefundAvailable(): bool
    {
        return $this->status->isSaleOpened() && "some logic";
    }

    /**
     * @return bool
     */
    public function isTicketsSaleOpened(): bool
    {
        return $this->status->isSaleOpened();
    }

    public function getId(): string
    {
        return $this->id;
    }
}