<?php

namespace AcePedigree;

use AceTools\Factory\DoctrineAwareFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'ace_tools' => [
        'table_prefixes' => [
            __NAMESPACE__ . '\Entity' => 'pedigree_',
        ],
    ],
    'controllers' => [
        'aliases' => [
            'index'  => Controller\IndexController::class,
            'dog'    => Controller\DogController::class,
            'image'  => Controller\ImageController::class,
            'person' => Controller\PersonController::class,
        ],
        'factories' => [
            Controller\IndexController::class  => DoctrineAwareFactory::class,
            Controller\DogController::class    => DoctrineAwareFactory::class,
            Controller\ImageController::class  => DoctrineAwareFactory::class,
            Controller\PersonController::class => DoctrineAwareFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'ace-pedigree' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/pedigree[/:controller[/:action]]',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
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
