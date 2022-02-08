<?php

namespace AthenaSodium\Controller;

use Laminas\View\Model\JsonModel;

class IndexController extends SodiumController
{
    public function aliveAction(): JsonModel
    {
        return new JsonModel(['hello' => $this -> sodiumService() -> hello()]);
    }
}