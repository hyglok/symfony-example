<?php
declare(strict_types=1);

namespace Identity\Model;

use Doctrine\ORM\Mapping as ORM;
use Identity\Model\Event\Registered;
use Lib\Model\AggregateRoot;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Identity\Repository\UserRepository")
 */
class User extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @param string $name
     * @param string $password
     */
    private function __construct(string $name, string $password)
    {
        $this->id = uuid_create();
        $this->name = $name;
        $this->password = $password; // hash in real project
    }

    /**
     * @param string $name
     * @param string $password
     *
     * @return static
     */
    public static function register(string $name, string $password): self
    {
        $user = new self($name, $password);
        $user->addEvent(new Registered($user->id, $user->name));

        return $user;
    }
}
