<?php

namespace AthenaSodium\Controller;

use AthenaSodium\Service\SodiumService;

class SodiumController extends \AthenaCore\Mvc\Controller\MvcController
{
    public function sodiumService():SodiumService
    {
        return $this->invokeService();
    }
}