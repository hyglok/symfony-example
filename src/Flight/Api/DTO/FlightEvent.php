<?php
declare(strict_types=1);

namespace Flight\Api\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class FlightEvent
{
    const SALES_COMPLETED = 'ticket_sales_completed';
    const FLIGHT_CANCELLED = 'flight_cancelled';

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public string $secretKey;
    /**
     * @Assert\NotNull()
     * @Assert\Uuid
     */
    public string $flightId;
    /**
     * @Assert\NotNull()
     * @Assert\DateTime
     */
    public string $triggeredAt;
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    public string $event;

    public function isSalesCompleted(): bool {
        return $this->event == self::SALES_COMPLETED;
    }

    public function isFlightCancelled(): bool {
        return $this->event == self::FLIGHT_CANCELLED;
    }
}