<?php
declare(strict_types=1);

namespace AthenaSodium\Model;

use Application\Model\ApplicationModel;
use AthenaSodium\Entity\User as UserEntity;

class User extends ApplicationModel
{
    public static function entityByEmail(string $email): UserEntity
    {
        return static ::byEmail($email);
    }

    public static function modelByUsername(string $username): self|null
    {
        return (new self(true)) -> select(['username' => $username]) -> current();
    }

    public static function modelByEmail(string $email): self|null
    {
        return static ::byEmail($email, true);
    }

    public static function modelByHash(string $hash): self|null
    {
        return static ::byHash($hash, true);
    }

    public static function entityByHash(string $hash): UserEntity
    {
        return static ::byHash($hash, false);
    }

    public static function byEmail(string $email, bool $useModelInsteadOfEntity = false): mixed
    {
        $instance = new self($useModelInsteadOfEntity);
        return $instance -> select(['email' => $email]) -> current();
    }

    public static function byToken(string $token, bool $useModelInsteadOfEntity = false): mixed
    {
        $instance = new self($useModelInsteadOfEntity);
        return $instance -> select(['token' => $token]) -> current();
    }

    public function setStatus(int $status): void
    {
        $this -> dataSet -> set('status', $status);
    }

    public function getPassword(): string
    {
        return $this -> getDataSet() -> get('password');
    }

    public function setPassword(?string $password): void
    {
        $this -> getDataSet() -> set('password', $password);
    }

    public function setPin(?string $pin): void
    {
        $this -> getDataSet() -> set('pin', $pin);
    }

    public function getFirstName():string
    {
        return $this -> getDataSet() -> get('first_name');
    }

    public function getEmail():string
    {
        return $this -> getDataSet() -> get('email');
    }

    public function getStatus():int
    {
        return (int)$this->getDataSet()->get('status');
    }
}