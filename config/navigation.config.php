<?php

return [
    [
        'label' => 'Ace Pedigree',
        'route' => 'ace-pedigree',
        'pages' => [
            [
                'label'  => 'Newest Entries',
                'route'  => 'ace-pedigree/data',
                'action' => 'recent',
            ],
            [
                'label'  => 'Dogs',
                'route'  => 'ace-pedigree/dogs',
                'pages'  => [
                    [
                        'label'  => 'Search',
                        'route'  => 'ace-pedigree/dogs/search',
                    ],
                    [
                        'label'  => 'Test Mating',
                        'route'  => 'ace-pedigree/data',
                        'action' => 'test-mating',
                    ],
                ],
            ],
            [
                'label'  => 'Persons',
                'route'  => 'ace-pedigree/persons',
                'pages'  => [
                    [
                        'label'  => 'Search',
                        'route'  => 'ace-pedigree/persons/search',
                    ],
                ],
            ],
            [
                'label'  => 'Statistics',
                'route'  => 'ace-pedigree/data',
                'action' => 'statistics',
                'pages'  => [
                    [
                        'label'  => 'Population Relationship Graph',
                        'route'  => 'ace-pedigree/data',
                        'action' => 'graph',
                    ],
                ],
            ],
        ],
    ],
];
