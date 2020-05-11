<?php
declare(strict_types=1);

namespace Flight\Application\Ticket;

use Symfony\Component\Validator\Constraints as Assert;

class Purchase
{
    /**
     * @Assert\Range(min = 1, max = 150, minMessage="Min seat is 1", maxMessage="Max seat is 150")
     */
    public int $seat;
    /**
     * @Assert\Uuid
     */
    public string $customerId;
    /**
     * @Assert\Uuid
     */
    public string $flightId;
    /**
     * @Assert\NotBlank
     */
    public string $firstName;
    /**
     * @Assert\NotBlank
     */
    public string $lastName;
    /**
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email")
     */
    public string $email;
}