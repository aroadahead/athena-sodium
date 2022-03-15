<?php
declare(strict_types=1);

use AthenaBridge\Laminas\Router\Http\Literal;
use AthenaSodium\Controller\AuthController;
use AthenaSodium\Controller\FacebookController;
use AthenaSodium\Controller\Factory\AuthControllerFactory;
use AthenaSodium\Controller\Factory\FacebookControllerFactory;
use AthenaSodium\Controller\Factory\GoogleControllerFactory;
use AthenaSodium\Controller\Factory\IndexControllerFactory;
use AthenaSodium\Controller\GoogleController;
use AthenaSodium\Controller\IndexController;
use AthenaSodium\Service\Factory\SodiumServiceFactory;
use Poseidon\Poseidon;

$laminas = Poseidon ::getCore() -> getLaminasManager();
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
            AuthController::class => AuthControllerFactory::class,
            FacebookController::class => FacebookControllerFactory::class,
            GoogleController::class => GoogleControllerFactory::class
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
                    'route' => $laminas -> route('alive', 'sodium'),
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'alive',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $laminas -> route('login', 'sodium'),
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'fbLogin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $laminas -> route('fb.login', 'sodium'),
                    'defaults' => [
                        'controller' => FacebookController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'googleLogin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $laminas -> route('gdata.login', 'sodium'),
                    'defaults' => [
                        'controller' => GoogleController::class,
                        'action' => 'login'
                    ]
                ]
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => $laminas -> route('logout', 'sodium'),
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action' => 'logout'
                    ]
                ]
            ]
        ]
    ]
];