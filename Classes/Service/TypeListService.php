<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class TypeListService
{
    public function getTcaList(array $config): array
    {
        $typesToShow = GeneralUtility::trimExplode(
            ',',
            BackendUtility::getPagesTSconfig($config['row']['pid'])['tx_schemarecords.']['types.']['show'] ?? '',
            true
         );

        if (empty($typesToShow)) {
            throw new \DomainException('The list of available types is empty', 1563790128);
        }

        $typesToShow = \array_unique($typesToShow);
        \sort($typesToShow);

        foreach ($typesToShow as $type) {
            $config['items'][] = [$type, $type];
        }

        return $config;
    }
}
