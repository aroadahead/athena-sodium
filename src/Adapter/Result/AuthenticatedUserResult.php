<?php

declare(strict_types=1);

namespace AthenaSodium\Adapter\Result;

use AthenaSodium\Model\User;
use JetBrains\PhpStorm\Pure;
use Laminas\Authentication\Result;

class AuthenticatedUserResult extends Result
{
    #[Pure] public function __construct(User $user, string $message)
    {
        parent ::__construct(parent::SUCCESS, $user, [$message]);
    }
}