<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\Schema\Core\Model\AbstractType;
use Brotkrueml\Schema\Provider\TypesProvider;
use Brotkrueml\Schema\Utility\Utility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class PresetsProvider
{
    private $propertiesNotToBeShown = [
        // is handled by an own field
        'mainEntityOfPage',
    ];

    public function getTypes(int $pageUid): array
    {
        $allTypes = (new TypesProvider())->getContentTypes();
        $presets = $this->getPresets($pageUid);

        if (empty($presets)) {
            return $allTypes;
        }

        $types = [];
        if ($presets['activeTerms'] ?? '') {
            $activeTerms = GeneralUtility::trimExplode(',', $presets['activeTerms'], true);
            foreach ($activeTerms as $term) {
                $types = \array_merge($types, \array_keys($presets['terms'][$term]['types'] ?? []));
            }

            $types = \array_unique($types);
            \sort($types);
            $types = \array_intersect($types, $allTypes);
        } else {
            $types = $allTypes;
        }

        return $types;
    }

    public function getPropertiesForType(int $pageUid, AbstractType $typeObject): array
    {
        $presets = $this->getPresets($pageUid);
        $allProperties = \array_diff($typeObject->getPropertyNames(), $this->propertiesNotToBeShown);

        if (empty($presets)) {
            return $allProperties;
        }

        $type = Utility::getClassNameWithoutNamespace(\get_class($typeObject));
        $properties = [];
        foreach ($presets['terms'] ?? [] as $term) {
            if (!isset($term['types'][$type])) {
                continue;
            }

            $propertiesForType = GeneralUtility::trimExplode(',', $term['types'][$type]);
            if (\array_search('*', $propertiesForType)) {
                return $allProperties;
            }

            $properties = \array_merge($properties, $propertiesForType);
        }

        $properties = \array_unique($properties);
        \sort($properties);

        return \array_intersect($properties, $allProperties);
    }

    private function getPresets(int $pageUid): array
    {
        $pageTS = BackendUtility::getPagesTSconfig($pageUid);
        $presets = GeneralUtility::removeDotsFromTS($pageTS['tx_schemarecords.']['presets.'] ?? []);

        if ($presets['activeTerms'] ?? '') {
            return $presets;
        }

        return [];
    }
}
