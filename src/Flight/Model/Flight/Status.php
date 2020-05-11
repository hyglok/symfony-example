<?php
declare(strict_types=1);

namespace Flight\Model\Flight;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Status
{
    const SALE_OPENED = 'sale_opened';
    const SALE_CLOSED = 'sale_closed';
    const CANCELLED = 'cancelled';

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

    public static function openSale(): self
    {
        return new self(self::SALE_OPENED);
    }

    public function closeSale()
    {
        if (!$this->isSaleOpened()) throw new \LogicException('Cant close sale for this flight');
        $this->status = self::SALE_CLOSED;
    }

    public function cancel()
    {
        if ($this->isCancelled()) {
            throw new \LogicException('Cant cancel twice');
        }
        $this->status = self::CANCELLED;
    }

    /**
     * @return bool
     */
    public function isSaleOpened(): bool
    {
        return $this->status === self::SALE_OPENED;
    }

    /**
     * @return bool
     */
    public function isSaleClosed(): bool
    {
        return $this->status === self::SALE_CLOSED;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === self::CANCELLED;
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::SALE_OPENED,
            self::CANCELLED,
            self::SALE_CLOSED,
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