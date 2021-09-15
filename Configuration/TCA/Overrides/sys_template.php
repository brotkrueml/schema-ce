<?php

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

defined('TYPO3') || die();

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'schema_records',
    'Configuration/TypoScript',
    'Schema Records'
);
