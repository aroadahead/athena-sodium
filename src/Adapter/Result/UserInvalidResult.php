<?php

declare(strict_types=1);

namespace AthenaSodium\Adapter;

use JetBrains\PhpStorm\Pure;
use Laminas\Authentication\Result;

class UserInvalidResult extends Result
{
    #[Pure] public function __construct(string $message)
    {
        parent ::__construct(parent::FAILURE, null, [$message]);
    }
}