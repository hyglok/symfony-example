<?php
declare(strict_types=1);

namespace Flight\Model\Flight;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="flights")
 * @ORM\Entity(repositoryClass="Flight\Repository\FlightRepository")
 */
class Flight
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private string $id;

    public function __construct()
    {
        $this->id = uuid_create();
    }

    //TODO: Flight info fields

    public function isRefundPossible(): bool
    {
        //some logic
        return true;
    }

    public function getId(): string
    {
        return $this->id;
    }
}