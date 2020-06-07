<?php

namespace AcePedigree;

use AceDbTools\Factory\DoctrineAwareFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Timestampable\TimestampableListener;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'ace_admin' => [
        'entities' => [
            'countries' => Entity\Country::class,
            'persons'   => Entity\Person::class,
            strtolower(str_replace(' ', '-', ANIMAL_PLURAL)) => Entity\Animal::class,
            strtolower(str_replace(' ', '-', HOUSE_PLURAL))  => Entity\House::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class      => DoctrineAwareFactory::class,
            Controller\AnimalController::class     => DoctrineAwareFactory::class,
            Controller\ImageController::class      => DoctrineAwareFactory::class,
            Controller\PersonController::class     => DoctrineAwareFactory::class,
            Controller\StatisticsController::class => DoctrineAwareFactory::class,
            Controller\TestMatingController::class => DoctrineAwareFactory::class,
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
                    'recent' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/recent',
                            'defaults' => [
                                'action'        => 'recent',
                            ],
                        ],
                    ],
                    'animals' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/' . strtolower(str_replace(' ', '-', ANIMAL_PLURAL)) . '[/:action]',
                            'defaults' => [
                                'controller'    => Controller\AnimalController::class,
                                'action'        => 'index',
                            ],
                            'constraints' => [
                                'action'        => '(search|suggest)',
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
                        ],
                    ],
                    'images' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/images',
                            'defaults' => [
                                'controller'    => Controller\ImageController::class,
                            ],
                        ],
                        'may_terminate' => false,
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
                            'upload' => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/upload',
                                    'defaults' => [
                                        'action'        => 'upload',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'persons' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/persons[/:action]',
                            'defaults' => [
                                'controller'    => Controller\PersonController::class,
                                'action'        => 'index',
                            ],
                            'constraints' => [
                                'action'        => '(search|suggest)',
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
                        ],
                    ],
                    'statistics' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/statistics[/:action]',
                            'defaults' => [
                                'controller'    => Controller\StatisticsController::class,
                                'action'        => 'index',
                            ],
                            'constraints' => [
                                // Weird ZF bug; population-data must come before population in order to match
                                'action'        => '(population-data|population)',
                            ],
                        ],
                    ],
                    'test-mating' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/test-mating',
                            'defaults' => [
                                'controller'    => Controller\TestMatingController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __NAMESPACE__ => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'species'          => View\Helper\Species::class,
            'entityLink'       => View\Helper\EntityLink::class,
            'ofaLink'          => View\Helper\OfaLink::class,
            'testMatingButton' => View\Helper\TestMatingButton::class,
            'printButton'      => View\Helper\PrintButton::class,
            'fuzzyDate'        => View\Helper\FuzzyDate::class,
            'pedigree'         => View\Helper\Pedigree::class,
        ],
        'factories' => [
            View\Helper\Species::class          => DoctrineAwareFactory::class,
            View\Helper\EntityLink::class       => InvokableFactory::class,
            View\Helper\OfaLink::class          => InvokableFactory::class,
            View\Helper\TestMatingButton::class => InvokableFactory::class,
            View\Helper\PrintButton::class      => InvokableFactory::class,
            View\Helper\FuzzyDate::class        => InvokableFactory::class,
            View\Helper\Pedigree::class         => InvokableFactory::class,
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
            Entity\Subscriber\AnimalSubscriber::class => InvokableFactory::class,
        ],
    ],
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    Entity\Subscriber\AnimalSubscriber::class,
                    LoggableListener::class,
                    TimestampableListener::class,
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
        'configuration' => [
            'orm_default' => [
                'filter_schema_assets_expression' => '~^(?!pedigree_animal_statistics)~',
            ],
        ],
    ],
];
