<?php
declare(strict_types=1);

namespace AthenaSodium\Model;

use Application\Model\ApplicationModel;
use AthenaSodium\Entity\LoginVerify as LoginVerifyEntity;

class LoginVerify extends ApplicationModel
{
    public static function modelByUserId(int $userid): self|null
    {
        return static ::byUserId($userid, true);
    }

    public static function entityByUserId(int $userid): LoginVerifyEntity
    {
        return static ::byUserId($userid, false);
    }

    public static function byUserId(int $userid, bool $useModelInsteadOfEntity = false)
    {
        $instance = new self($useModelInsteadOfEntity, 'login_verify');
        return $instance -> select(['userid' => $userid]) -> current();
    }

    public function getVerifyToken(): string
    {
        return $this -> getDataSet() -> get('verify_token');
    }

    public function setRetryCount(int $retryCount): void
    {
        $this -> getDataSet() -> set('retry_count', $retryCount);
    }

    public function getRetryCount(): int
    {
        return (int)$this -> getDataSet() -> get('retry_count');
    }
}