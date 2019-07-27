<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'frontend' => [
        'brotkrueml/schema_ce/type-embedding' => [
            'target' => \Brotkrueml\SchemaCe\Middleware\TypeEmbedding::class,
            'before' => [
                'typo3/cms-frontend/content-length-headers',
            ],
            'after' => [
                'brotkrueml/schema/webpage-type',
            ]
        ],
    ],
];
