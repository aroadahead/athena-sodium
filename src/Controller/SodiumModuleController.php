<?php

declare(strict_types=1);

namespace AthenaSodium\Controller;

use Application\Controller\ModuleController;
use AthenaSodium\Service\SodiumService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SodiumModuleController extends ModuleController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function sodiumService(): SodiumService
    {
        return $this -> invokeService();
    }
}