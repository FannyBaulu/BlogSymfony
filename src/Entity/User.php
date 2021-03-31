<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var string|null
     * @ORM\Column(unique=true)
     */
    private ?string $email = null;

    /**
     * @var string|null
     * @ORM\Column
     */
    private ?string $password = null;

    /**
     * @var string|null
     * @ORM\Column(unique=true)
     */
    private ?string $pseudo = null;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $registeredAt;

    /**
     * User constructor
     * @throws Exception
     */
    public function __construct()
    {
        $this->registeredAt = new DateTimeImmutable();
    }

    /**
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of email
     *
     * @return  string|null
     */ 
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string|null  $email
     *
     */ 
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Get the value of password
     *
     * @return  string|null
     */ 
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string|null  $password
     */ 
    public function setPassword($password) : void
    {
        $this->password = $password;
    }

    /**
     * Get the value of pseudo
     *
     * @return  string|null
     */ 
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @param  string|null  $pseudo
     */ 
    public function setPseudo($pseudo) : void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Get the value of registeredAt
     *
     * @return  DateTimeImmutable
     */ 
    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    /**
     * Set the value of registeredAt
     *
     * @param  DateTimeImmutable  $registeredAt
     */ 
    public function setRegisteredAt(DateTimeImmutable $registeredAt): void
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        
    }
}
