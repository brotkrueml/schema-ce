<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property',
        'label' => 'name',
        'formattedLabel_userFunc' => \Brotkrueml\SchemaRecords\Service\PropertyLabelService::class . '->getLabel',
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
        ],
        'type' => 'variant',
        'searchFields' => 'name,single_value,url',
        'iconfile' => 'EXT:schema_records/Resources/Public/Icons/tx_schemarecords_domain_model_property.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, variant, name, single_value, url, type_reference',
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
                'foreign_table' => 'tx_schemarecords_domain_model_property',
                'foreign_table_where' => 'AND {#tx_schemarecords_domain_model_property}.{#pid}=###CURRENT_PID### AND {#tx_schemarecords_domain_model_property}.{#sys_language_uid} IN (-1,0)',
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

        'variant' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_SINGLE_VALUE,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_SINGLE_VALUE
                    ],
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_URL,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_URL
                    ],
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_IMAGE,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_IMAGE
                    ],
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_BOOLEAN,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_BOOLEAN
                    ],
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_TYPE_REFERENCE,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_TYPE_REFERENCE
                    ],
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_DATETIME,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_DATETIME
                    ],
                    [
                        'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_DATE,
                        \Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_DATE
                    ],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.name',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                ],
                'itemsProcFunc' => \Brotkrueml\SchemaRecords\Service\PropertyListService::class . '->getTcaList',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'single_value' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.single_value',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'valuePicker' => [
                    'items' => [
                        ['LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:value_picker.page.title', '{page:title}'],
                        ['LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:value_picker.page.subtitle', '{page:subtitle}'],
                        ['LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:value_picker.page.abstract', '{page:abstract}'],
                        ['LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:value_picker.page.description', '{page:description}'],
                        ['LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:value_picker.page.last_updated', '{page:lastUpdated(datetime)}'],
                    ],
                ],
            ],
        ],
        'url' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.url',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 50,
                'max' => 1024,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel',
                        ],
                    ],
                ],
                'softref' => 'typolink'
            ]
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;filePalette
                                ',
                            ],
                        ],
                    ],
                    'maxitems' => 1,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'flag' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.flag',
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
        'type_reference' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.type_reference',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_schemarecords_domain_model_type',
                'maxitems' => 1,
                'size' => 1,
            ],
        ],
        'reference_only' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.reference_only',
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
        'timestamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.datetime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ],
        ],

        'parent' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
    'types' => [
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_SINGLE_VALUE => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, single_value,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_URL => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, url, 
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_IMAGE => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, image, 
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_BOOLEAN => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, flag, 
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_TYPE_REFERENCE => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, type_reference, reference_only,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_DATETIME => [
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, timestamp, 
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
        (string)\Brotkrueml\SchemaRecords\Domain\Model\Property::VARIANT_DATE => [
            'columnsOverrides' => [
                'timestamp' => [
                    'label' => 'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.date',
                    'config' => [
                        'eval' => 'date',
                    ],
                ],
            ],
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource,
                name, variant, timestamp, 
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden,
            '
        ],
    ],
];
