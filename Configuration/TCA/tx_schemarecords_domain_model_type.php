<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_type',
        'label' => 'schema_type',
        'label_userFunc' => \Brotkrueml\SchemaRecords\Service\TypeLabelService::class . '->getLabel',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'schema_id',
        'iconfile' => 'EXT:schema_records/Resources/Public/Icons/tx_schemarecords_domain_model_type.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, schema_type, schema_id, webpage_mainentity, properties',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_schemarecords_domain_model_type',
                'foreign_table_where' => 'AND {#tx_schemarecords_domain_model_type}.{#pid}=###CURRENT_PID### AND {#tx_schemarecords_domain_model_type}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'schema_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_type.schema_type',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                ],
                'itemsProcFunc' => \Brotkrueml\SchemaRecords\Service\TypeListService::class . '->getTcaList',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'schema_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_type.schema_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'webpage_mainentity' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_type.webpage_mainentity',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled',
                    ]
                ],
            ],
        ],
        'properties' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_type.properties',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_schemarecords_domain_model_property',
                'foreign_field' => 'parent',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'levelLinksPosition' => 'bottom',
                    'showPossibleLocalizationRecords' => true,
                    'showSynchronizationLink' => true,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ],
            ],

        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                schema_type,
                schema_id,
                webpage_mainentity,
                properties,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                starttime, endtime, hidden,
            '
        ],
    ],
];
