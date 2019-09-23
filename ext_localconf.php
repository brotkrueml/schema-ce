<?php
defined('TYPO3_MODE') || die('Access denied.');

$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
    \Brotkrueml\SchemaRecords\Middleware\TypeEmbedding::class,
    'placeholderSubstitution',
    \Brotkrueml\SchemaRecords\Slots\PagePlaceholderSubstitutionSlot::class,
    'substitute'
);
