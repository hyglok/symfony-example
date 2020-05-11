<?php
declare(strict_types=1);

namespace Flight\Model\Flight;

use Doctrine\ORM\Mapping as ORM;
use Flight\Model\Flight\Event\Registered;
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

    public function isRefundAvailable(): bool
    {
        //TODO: some logic
        return true;
    }

    public function isTicketsSaleOpened(): bool
    {
        //TODO: some logic
        return true;
    }

    public function getId(): string
    {
        return $this->id;
    }
}