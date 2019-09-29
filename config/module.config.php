<?php

namespace AcePedigree;

use AceTools\Factory\DoctrineAwareFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
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
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/pedigree',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'data' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [
                                'action'        => '(recent|statistics|graph|test-mating)',
                            ],
                        ],
                    ],
                    'dogs' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/dogs',
                            'defaults' => [
                                'controller'    => Controller\DogController::class,
                                'action'        => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'view' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '[/:action]/:id',
                                    'defaults' => [
                                        'action'        => 'view',
                                    ],
                                    'constraints' => [
                                        'action'        => 'print',
                                        'id'            => '[0-9]+',
                                    ],
                                ],
                            ],
                            'search' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/:action',
                                    'constraints' => [
                                        'action'        => '(search|check)',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'images' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/images/:id',
                            'defaults' => [
                                'controller'    => Controller\ImageController::class,
                                'action'        => 'view',
                            ],
                            'constraints' => [
                                'id'            => '[0-9]+',
                            ],
                        ],
                    ],
                    'upload' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/images/upload',
                            'defaults' => [
                                'controller'    => Controller\ImageController::class,
                                'action'        => 'upload',
                            ],
                        ],
                    ],
                    'persons' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/persons',
                            'defaults' => [
                                'controller'    => Controller\PersonController::class,
                                'action'        => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'view' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/:id',
                                    'defaults' => [
                                        'action'        => 'view',
                                    ],
                                    'constraints' => [
                                        'id'            => '[0-9]+',
                                    ],
                                ],
                            ],
                            'search' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/:action',
                                    'constraints' => [
                                        'action'        => '(search|check)',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'AcePedigree' => __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'entityLink'       => Helper\EntityLink::class,
            'ofaLink'          => Helper\OfaLink::class,
            'testMatingButton' => Helper\TestMatingButton::class,
            'fuzzyDate'        => Helper\FuzzyDate::class,
            'pedigree'         => Helper\Pedigree::class,
        ],
        'factories' => [
            Helper\EntityLink::class       => InvokableFactory::class,
            Helper\OfaLink::class          => InvokableFactory::class,
            Helper\TestMatingButton::class => InvokableFactory::class,
            Helper\FuzzyDate::class        => InvokableFactory::class,
            Helper\Pedigree::class         => InvokableFactory::class,
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __NAMESPACE__ => __DIR__ . '/../asset',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Subscriber\DogSubscriber::class => InvokableFactory::class,
        ],
    ],
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    Subscriber\DogSubscriber::class,
                ],
            ],
        ],
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
        'migrations_configuration' => [
            'orm_default' => [
                'name'      => 'AcePedigree Migrations',
                'directory' => __DIR__ . '/../src/Migrations',
                'namespace' => __NAMESPACE__ . '\Migrations',
                'table'     => 'pedigree_schema',
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'filter_schema_assets_expression' => '~^(?!pedigree_dog_statistics)~',
            ],
        ],
    ],
];
