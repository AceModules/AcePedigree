<?php

namespace AcePedigree;

use AceTools\Factory\DoctrineAwareFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'ace_tools' => [
        'table_prefixes' => [
            __NAMESPACE__ . '\Entity' => 'pedigree_',
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => DoctrineAwareFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'ace-pedigree' => [
                'type'    => 'Literal',
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/pedigree',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // You can place additional routes that match under the
                    // route defined above here.
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'AcePedigree' => __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
