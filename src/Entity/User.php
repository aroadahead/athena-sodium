<?php
declare(strict_types=1);

namespace AthenaSodium\Entity;

use Application\Entity\ApplicationEntity;

class User extends ApplicationEntity
{
    /**
     * Return the user pin
     * @return int
     */
    public function getPin(): int
    {
        return (int)$this -> get('pin');
    }

    /**
     * Return the user email address
     * @return string
     */
    public function getEmailaddress(): string
    {
        return $this -> get('email');
    }

    /**
     * Return the user email address
     * @return string
     */
    public function getEmail(): string
    {
        return $this -> getEmailaddress();
    }

    /**
     * Return the user username
     * @return string
     */
    public function getUsername(): string
    {
        return $this -> get('username');
    }

    /**
     * Return the user first name
     * @return string
     */
    public function getFirstName(): string
    {
        return $this -> get('first_name');
    }

    /**
     * Return the user middle name
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this -> get('middle_name');
    }

    /**
     * Return the user last name
     * @return string
     */
    public function getLastName(): string
    {
        return $this -> get('last_name');
    }

    /**
     * Set the user pin validation
     * @param bool $true
     * @return void
     */
    public function setPinValidated(bool $true): void
    {
        $this -> set('pin_validated', $true);
    }

    public function setJustLoggedIn(bool $true)
    {
        $this -> set('just_logged_in', $true);
    }

    public function getPassword(): string
    {
        return $this -> get('password');
    }

    public function setPassword(?string $password): void
    {
        $this -> set('password', $password);
    }

    public function setPin(?string $pin): void
    {
        $this -> set('pin', $pin);
    }
}