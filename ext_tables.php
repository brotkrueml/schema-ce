<?php
defined('TYPO3_MODE') || die('Access denied.');

(function ($extensionKey='schema_records') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemarecords_domain_model_type');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemarecords_domain_model_property');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import "EXT:' . $extensionKey . '/Configuration/TSconfig/Page/"
        @import "EXT:' . $extensionKey . '/Configuration/TSconfig/Page/Presets/"
    ');

})();
