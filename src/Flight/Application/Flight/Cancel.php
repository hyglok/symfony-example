<?php
declare(strict_types=1);

namespace Flight\Application\Flight;

use Symfony\Component\Validator\Constraints as Assert;

class Cancel
{
    /**
     * @Assert\Uuid
     */
    public string $flightId;

    public function __construct(string $flightId)
    {
        $this->flightId = $flightId;
    }
}