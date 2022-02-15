<?php

namespace AthenaSodium\Controller\Factory;

use AthenaSodium\Controller\AuthController;
use Interop\Container\ContainerInterface;

class AuthControllerFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new AuthController($container);
    }
}