<?php

namespace AthenaSodium\Adapter;

use AthenaSodium\Adapter\Result\AuthenticatedUserResult;
use AthenaSodium\Model\User;
use AthenaSodium\Service\SodiumService;
use http\Exception\RuntimeException;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use Psr\Container\ContainerInterface;

class AuthAdapter implements AdapterInterface
{
    /**
     * User status active
     *
     * @var int
     */
    public const STATUS_ACTIVE = 1;

    /**
     * User status deleted
     *
     * @var int
     */
    public const STATUS_DELETED = 0;

    /**
     * User status suspended
     *
     * @var int
     */
    public const STATUS_SUSPENDED = 2;

    private string $credential;
    private string $password;

    /**
     * Set email
     * @param string $credential
     * @return void
     */
    public function setCredential(string $credential): void
    {
        $this -> credential = $credential;
    }

    /**
     * Set password
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this -> password = $password;
    }

    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * @inheritDoc
     */
    public function authenticate()
    {
        $facade = $this -> container -> get('conf') -> facade();
        if ($facade -> getApplicationConfig('auth.use_email_for_login')) {
            $user = User ::modelByEmail($this -> credential);
        } elseif ($facade -> getApplicationConfig('auth.use_username_for_login')) {
            $user = User ::modelByUsername($this -> credential);
        } else {
            throw new RuntimeException("Invalid authentication type. Must be email or username");
        }
        if ($user === null) {
            return new UserInvalidResult("User not found.");
        } elseif ($user -> getStatus() === self::STATUS_DELETED) {
            return new UserInvalidResult("User is retired");
        } elseif ($user -> getStatus() === self::STATUS_SUSPENDED) {
            return new UserInvalidResult("User is suspended");
        }

        /* @var $sodiumService SodiumService */
        $sodiumService = $this -> container -> get('modules') -> moduleLoader() -> load('athena-sodium');
        if ($sodiumService -> validatePassword($user -> getPassword(), $this -> password)) {
            $user -> setPassword(null);
            $user -> setPin(null);
            return new AuthenticatedUserResult($user, "Authenticated Successfully.");
        }
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ["Invalid Credentials."]);
    }
}