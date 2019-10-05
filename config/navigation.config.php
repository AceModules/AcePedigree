<?php

return [
    [
        'label'  => 'Ace Pedigree',
        'route'  => 'ace-pedigree',
        'pages'  => [
            [
                'label'  => 'Recent Updates',
                'route'  => 'ace-pedigree/recent',
            ],
            [
                'label'  => 'Dogs',
                'route'  => 'ace-pedigree/dogs',
                'pages'  => [
                    [
                        'label'  => 'Search',
                        'route'  => 'ace-pedigree/dogs',
                        'action' => 'search',
                    ],
                ],
            ],
            [
                'label'  => 'Persons',
                'route'  => 'ace-pedigree/persons',
                'pages'  => [
                    [
                        'label'  => 'Search',
                        'route'  => 'ace-pedigree/persons',
                        'action' => 'search',
                    ],
                ],
            ],
            [
                'label'  => 'Statistics',
                'route'  => 'ace-pedigree/statistics',
                'pages'  => [
                    [
                        'label'  => 'Population',
                        'route'  => 'ace-pedigree/statistics',
                        'action' => 'population',
                    ],
                ],
            ],
            [
                'label'  => 'Test Mating',
                'route'  => 'ace-pedigree/test-mating',
            ],
        ],
    ],
];
