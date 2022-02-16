<?php

namespace AthenaSodium\Adapter;

use AthenaSodium\Adapter\Result\AuthenticatedUserResult;
use AthenaSodium\Model\User;
use AthenaSodium\Service\SodiumService;
use http\Exception\RuntimeException;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\Result;
use Psr\Container\ContainerInterface;

class AuthAdapter extends CredentialTreatmentAdapter implements AdapterInterface
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

    public function __construct(protected ContainerInterface $container)
    {
        $configFacade = $this -> container -> get('conf') -> facade();
        parent ::__construct(
            $this -> container -> get('db') -> masterAdapter(),
            $configFacade -> getApplicationConfig('auth.db_table_name'),
            $configFacade -> getApplicationConfig('auth.db_table_identity_column'),
            $configFacade -> getApplicationConfig('auth.db_table_credential_column')
        );
    }

    /**
     * @inheritDoc
     */
    public function authenticate():mixed
    {
        $facade = $this -> container -> get('conf') -> facade();
        if ($facade -> getApplicationConfig('auth.use_email_for_login')) {
            $user = User ::modelByEmail($this -> identity);
        } elseif ($facade -> getApplicationConfig('auth.use_username_for_login')) {
            $user = User ::modelByUsername($this -> identity);
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
        if ($sodiumService -> validatePassword($user -> getPassword(), $this -> credential)) {
            $user -> setPassword(null);
            $user -> setPin(null);
            return new AuthenticatedUserResult($user, "Authenticated Successfully.");
        }
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ["Invalid Credentials."]);
    }
}