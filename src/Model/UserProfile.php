<?php

declare(strict_types=1);

namespace AthenaSodium\Model;

use Application\Model\ApplicationModel;

class UserProfile extends ApplicationModel
{

    public static function byFbId(mixed $id)
    {
        return (new self(false)) -> select(['facebook' => $id]) -> current();
    }

    public static function byGoogleId(mixed $id)
    {
        return (new self(false)) -> select(['google' => $id]) -> current();
    }
}