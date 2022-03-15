<?php

namespace AthenaSodium\Session\Container;

class FacebookContainer extends \Application\Session\Container\ApplicationSession
{
    public function __construct()
    {
        parent ::__construct('fbAuth');
    }

    public function setAccessToken(string $accessToken): void
    {
        $this -> offsetSet('accessToken', $accessToken);
    }

    public function setUserId(string $userId): void
    {
        $this -> offsetSet('userId', $userId);
    }

    public function setUserName(string $userName): void
    {
        $this -> offsetSet('userName', $userName);
    }

    public function setUserEmailAddress(string $userEmailAddress): void
    {
        $this -> offsetSet('userEmailAddress', $userEmailAddress);
    }

    public function setUserImage(string $userImage): void
    {
        $this -> offsetSet('userImage', $userImage);
    }
}