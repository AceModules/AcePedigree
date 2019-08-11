<?php

namespace AcePedigree;

use AceTools\Factory\DoctrineAwareFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'ace_tools' => [
        'table_prefixes' => [
            __NAMESPACE__ . '\Entity' => 'pedigree_',
        ],
    ],
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
                                'action'        => '(recent|statistics|test-mating)',
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
            'entityLink' => Helper\EntityLink::class,
            'fuzzyDate'  => Helper\FuzzyDate::class,
        ],
        'factories' => [
            Helper\EntityLink::class => InvokableFactory::class,
            Helper\FuzzyDate::class  => InvokableFactory::class,
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
