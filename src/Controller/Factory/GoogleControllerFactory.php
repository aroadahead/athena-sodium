<?php

namespace AthenaSodium\Controller\Factory;

use AthenaSodium\Controller\GoogleController;
use Interop\Container\ContainerInterface;

class GoogleControllerFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new GoogleController($container);
    }
}