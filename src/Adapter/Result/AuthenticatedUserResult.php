<?php

namespace AthenaSodium\Adapter\Result;

use AthenaSodium\Model\User;
use JetBrains\PhpStorm\Pure;

class AuthenticatedUserResult extends \Laminas\Authentication\Result
{
    #[Pure] public function __construct(User $user, string $message)
    {
        parent ::__construct(parent::SUCCESS, $user, [$message]);
    }
}