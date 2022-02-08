<?php
declare(strict_types=1);

use AthenaSodium\Controller\Factory\IndexControllerFactory;
use AthenaSodium\Controller\IndexController;
use AthenaSodium\Service\Factory\SodiumServiceFactory;
use Laminas\Router\Http\Literal;
use Poseidon\Poseidon;

$lamins = Poseidon ::getCore() -> getLaminasManager();
return [
    'view_manager' => [
        'template_map' => [],
        'template_path_stack' => [
            __DIR__ . '/../view'
        ]
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class
        ]
    ],
    'service_manager' => [
        'factories' => [
            'module.service.athena-sodium' => SodiumServiceFactory::class,
        ]
    ],
    'translator' => [],
    'view_helpers' => [],
    'router' => [
        'routes' => [
            'sodium.alive' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $lamins -> route('alive', 'sodium'),
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'alive',
                    ],
                ],
            ],
        ]
    ]
];