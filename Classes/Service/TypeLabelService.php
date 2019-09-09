<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\SchemaRecords\Domain\Model\Property;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class TypeLabelService
{
    public function getLabel(&$parameters): void
    {
        $title = '';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_schemarecords_domain_model_property');
        $properties = $queryBuilder
            ->select('name', 'single_value')
            ->from('tx_schemarecords_domain_model_property')
            ->where(
                $queryBuilder->expr()->eq(
                    'parent',
                    $queryBuilder->createNamedParameter($parameters['row']['uid'], \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'variant',
                    $queryBuilder->createNamedParameter(Property::VARIANT_SINGLE_VALUE, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'name',
                    $queryBuilder->createNamedParameter('name')
                )
            )
            ->execute();

        if ($row = $properties->fetch()) {
            $title = $row['single_value'];
        } elseif (!empty($parameters['row']['schema_id'])) {
            $title = $parameters['row']['schema_id'];
        }

        $title .= ' (' . $parameters['row']['schema_type'];

        if ((bool)$parameters['row']['webpage_mainentity']) {
            $title .= ', '
                . $this->getLanguageService()->sL(
                    'LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_type.webpage_mainentity.short'
                );
        }

        $title .= ')';

        $parameters['title'] = trim($title);
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
