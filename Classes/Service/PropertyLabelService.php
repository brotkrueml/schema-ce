<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Localization\LanguageService;

final class PropertyLabelService
{
    public function getLabel(&$parameters): void
    {
        $parameters['title'] = sprintf(
            '%s (%s)',
            $parameters['row']['name'][0],
            $this->getLanguageService()->sL(
                'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.' . $parameters['row']['variant'][0]
            )
        );
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
