<?php
declare(strict_types=1);

namespace Shared\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Email
{
    /**
     * @ORM\Column(type="string")
     */
    private string $address;

    public function __construct(string $address)
    {
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not a valid email address', $address)
            );
        }

        $this->address = strtolower($address);
    }

    public function address(): string
    {
        return $this->address;
    }
}