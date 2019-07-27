<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('schema_ce', 'Configuration/TypoScript', 'Schema Content Element');
    }
);
