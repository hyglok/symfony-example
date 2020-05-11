<?php
declare(strict_types=1);

namespace Flight\Model\Reservation;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Status
{
    const RESERVED = 'reserved';
    const CANCELLED = 'cancelled';
    const PAID = 'paid';

    /**
     * @ORM\Column(name="status", type="string", length=20)
     */
    protected $status;

    /**
     * @param string $status
     */
    private function __construct(string $status)
    {
        if (!in_array($status, self::getStatuses(), true)) {
            throw new \LogicException('Unknown status');
        }
        $this->status = $status;
    }

    public static function reserve(): self
    {
        return new self(self::RESERVED);
    }

    public function cancel()
    {
        if ($this->isPaid()) {
            throw new \LogicException('Cant cancel paid reservation');
        }
        $this->status = self::CANCELLED;
    }

    public function pay()
    {
        if (!$this->isReserved()) {
            throw new \LogicException('Cant pay this reservation');
        }
        $this->status = self::PAID;
    }

    /**
     * @return bool
     */
    public function isReserved()
    {
        return $this->status === self::RESERVED;
    }

    public function isPaid()
    {
        return $this->status === self::PAID;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::CANCELLED;
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::RESERVED,
            self::CANCELLED,
            self::PAID,
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->status;
    }
}