<?php
declare(strict_types=1);

namespace AthenaSodium\Model;

use Application\Model\ApplicationModel;

class UserAction extends ApplicationModel
{
    public static function saveUserAction(int $userid, string $resource, string $action, int $recordId = 0)
    {
        $instance = new self(false);
        $instance -> setUserid($userid);
        $instance -> setResource($resource);
        $instance -> setAction($action);
        $instance -> setRecordid($recordId);
        $instance -> save(false);
    }
}