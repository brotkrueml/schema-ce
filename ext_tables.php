<?php
defined('TYPO3_MODE') || die('Access denied.');

(function ($extensionKey='schema_records') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemarecords_domain_model_type');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemarecords_domain_model_property');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import "EXT:' . $extensionKey . '/Configuration/TSconfig/Page/"
        @import "EXT:' . $extensionKey . '/Configuration/TSconfig/Page/Info/"
        @import "EXT:' . $extensionKey . '/Configuration/TSconfig/Page/Presets/"
    ');

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1584554889] = [
        'nodeName' => 'schemaRecordsTypeLinks',
        'priority' => 40,
        'class' => Brotkrueml\SchemaRecords\FormEngine\Elements\TypeLinks::class,
    ];
})();
