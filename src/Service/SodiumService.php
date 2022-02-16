<?php

namespace AthenaSodium\Service;

use AthenaCore\Mvc\Service\MvcService;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Http\PhpEnvironment\Response;

class SodiumService extends MvcService
{
    public function createPassword(string $passToHash): string
    {
        $facade = $this -> container -> get('conf') -> facade();
        $bcrypt = new Bcrypt();
        $bcrypt -> setSalt($facade -> getApplicationConfig('auth.bcrypt.salt'));
        $bcrypt -> setCost($facade -> getApplicationConfig('auth.bcrypt.cost'));
        return $bcrypt -> create($passToHash);
    }

    public function validatePassword(string $storedPass, string $attemptedPass): bool
    {
        $facade = $this -> container -> get('conf') -> facade();
        $bcrypt = new Bcrypt();
        $bcrypt -> setSalt($facade -> getApplicationConfig('auth.bcrypt.salt'));
        $bcrypt -> setCost($facade -> getApplicationConfig('auth.bcrypt.cost'));
        return $bcrypt -> verify($attemptedPass, $storedPass);
    }
}