<?php
defined('TYPO3') || die();

(function () {
    $signalSlotDispatcher = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class
    );
    $signalSlotDispatcher->connect(
        Brotkrueml\SchemaRecords\Aspect\RecordsAspect::class,
        'placeholderSubstitution',
        Brotkrueml\SchemaRecords\Slots\PagePlaceholderSubstitutionSlot::class,
        'substitute'
    );

    // Use of internal hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/schema']['registerAspect']['records']
        = Brotkrueml\SchemaRecords\Aspect\RecordsAspect::class;
})();
