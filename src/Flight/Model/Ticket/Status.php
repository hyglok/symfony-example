<?php
declare(strict_types=1);

namespace Flight\Model\Ticket;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Status
{
    const PURCHASED = 'purchased';
    const CANCELLED = 'cancelled';
    const REFUNDED = 'refunded';

    /**
     * @ORM\Column(name="status", type="string", length=20)
     */
    protected $status;

    /**
     * @param string $status
     */
    private function __construct(string $status)
    {
        if (!in_array($status, self::getStatuses(), true)) throw new \LogicException('Unknown status');
        $this->status = $status;
    }

    public static function purchase(): self
    {
        return new self(self::PURCHASED);
    }

    public function refund()
    {
        if (!($this->isPurchased() || $this->isCancelled())) throw new \LogicException('Cant refund');
        $this->status = self::REFUNDED;
    }

    public function cancel()
    {
        if (!$this->isPurchased()) throw new \LogicException('Cant cancel');
        $this->status = self::CANCELLED;
    }

    /**
     * @return bool
     */
    public function isPurchased()
    {
        return $this->status === self::PURCHASED;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::CANCELLED;
    }

    /**
     * @return bool
     */
    public function isRefunded()
    {
        return $this->status === self::REFUNDED;
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::PURCHASED,
            self::CANCELLED,
            self::REFUNDED,
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