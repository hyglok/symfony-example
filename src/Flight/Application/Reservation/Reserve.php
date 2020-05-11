<?php
declare(strict_types=1);

namespace Flight\Application\Reservation;

use Symfony\Component\Validator\Constraints as Assert;

class Reserve
{
    /**
     * @Assert\NotNull()
     * @Assert\Range(min = 1, max = 150, minMessage="Min seat is 1", maxMessage="Max seat is 150")
     */
    public int $seat;
    /**
     * @Assert\NotNull()
     * @Assert\Uuid
     */
    public string $customerId;
    /**
     * @Assert\NotNull()
     * @Assert\Uuid
     */
    public string $flightId;
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank
     */
    public string $firstName;
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank
     */
    public string $lastName;
    /**
     * @Assert\NotNull()
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email")
     */
    public string $email;
}