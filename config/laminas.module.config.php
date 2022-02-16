<?php
declare(strict_types=1);

use AthenaSodium\Controller\AuthController;
use AthenaSodium\Controller\Factory\AuthControllerFactory;
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
            IndexController::class => IndexControllerFactory::class,
            AuthController::class => AuthControllerFactory::class
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
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $lamins -> route('login', 'sodium'),
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $lamins -> route('logout', 'sodium'),
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'logout'
                    ]
                ]
            ]
        ]
    ]
];