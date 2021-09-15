<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Schema Records',
    'description' => 'schema.org structured data for your website with records',
    'category' => 'fe',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'experimental',
    'version' => '0.5.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.11-11.5.99',
            'schema' => '2.0.0-2.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Brotkrueml\\SchemaRecords\\' => 'Classes'
        ]
    ],
];
