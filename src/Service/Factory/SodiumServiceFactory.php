<?php

namespace AthenaSodium\Service\Factory;

use AthenaSodium\Service\SodiumService;
use Interop\Container\ContainerInterface;

class SodiumServiceFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new SodiumService($container);
    }
}