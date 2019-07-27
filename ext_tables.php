<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extensionKey)
    {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemace_domain_model_type');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemace_domain_model_property');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '@import "EXT:' . $extensionKey . '/Configuration/TSconfig/Page/"'
        );

    },
    'schema_ce'
);
