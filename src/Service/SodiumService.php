<?php
declare(strict_types=1);

namespace AthenaSodium\Service;

use AthenaCore\Mvc\Service\MvcService;
use Laminas\Crypt\Password\Bcrypt;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Sodium service
 */
class SodiumService extends MvcService
{
    /**
     * Create a password.
     *
     * @param string $passToHash
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createPassword(string $passToHash): string
    {
        $facade = $this -> container -> get('conf') -> facade();
        $bcrypt = new Bcrypt();
        $bcrypt -> setSalt($facade -> getApplicationConfig('auth.bcrypt.salt'));
        $bcrypt -> setCost($facade -> getApplicationConfig('auth.bcrypt.cost'));
        return $bcrypt -> create($passToHash);
    }

    /**
     * Validate a password.
     *
     * @param string $storedPass
     * @param string $attemptedPass
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function validatePassword(string $storedPass, string $attemptedPass): bool
    {
        $facade = $this -> container -> get('conf') -> facade();
        $bcrypt = new Bcrypt();
        $bcrypt -> setSalt($facade -> getApplicationConfig('auth.bcrypt.salt'));
        $bcrypt -> setCost($facade -> getApplicationConfig('auth.bcrypt.cost'));
        return $bcrypt -> verify($attemptedPass, $storedPass);
    }
}