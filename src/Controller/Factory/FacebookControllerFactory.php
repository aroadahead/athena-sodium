<?php

namespace AthenaSodium\Controller\Factory;

use AthenaSodium\Controller\FacebookController;
use Interop\Container\ContainerInterface;
use ReflectionException;

class FacebookControllerFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new FacebookController($container);
    }
}