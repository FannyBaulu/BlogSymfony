<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Credentials
 * @package App\DataTransferObject
 */
class Credentials
{
    /**
     * @var string|null
     * Assert\NotBlank
     */
    private ?string $username = null;

    /**
     * @var string|null
     * Assert\NotBlank
     */
    private ?string $password = null;

    /**
     * Credentials constructor
     *
     * @param string|null $username
     */
    public function __construct(?string $username = null)
    {
        $this->username = $username;
    }

    

    /**
     * Get the value of username
     *
     * @return  string|null
     */ 
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param  string|null  $username
     *
     */ 
    public function setUsername(?string $username): void
    {
        $this->username = $username;
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
     *
     */ 
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}