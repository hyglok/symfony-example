<?php
declare(strict_types=1);

namespace Flight\Model;

use Lib\Model\Email;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Passenger
{
    /**
     * @ORM\Column(type="string", length=150, name="first_name")
     */
    private string $firstName;
    /**
     * @ORM\Column(type="string", length=150, name="last_name")
     */
    private string $lastName;
    /**
     * @ORM\Embedded(class="Lib\Model\Email")
     */
    private Email $email;

    /**
     * Passenger constructor.
     *
     * @param string $firstName
     * @param string $lastName
     * @param Email $email
     */
    public function __construct(string $firstName, string $lastName, Email $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }
}