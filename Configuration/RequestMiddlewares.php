<?php
return [
    'frontend' => [
        'brotkrueml/schema_records/type-embedding' => [
            'target' => \Brotkrueml\SchemaRecords\Middleware\TypeEmbedding::class,
            'before' => [
                'typo3/cms-frontend/content-length-headers',
            ],
            'after' => [
                'brotkrueml/schema/webpage-type',
            ]
        ],
    ],
];
