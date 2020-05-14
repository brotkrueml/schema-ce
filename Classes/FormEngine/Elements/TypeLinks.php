<?php
declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\FormEngine\Elements;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class TypeLinks extends AbstractNode
{
    private $links = [];

    private $selectedType;

    public function render()
    {
        $resultArray = $this->initializeResultArray();

        $this->selectedType = $this->data['databaseRow']['schema_type'][0];

        if (empty($this->selectedType)) {
            return $resultArray;
        }

        $this->buildLinkForSchemaOrg();
        $this->buildLinksFromTSConfig($this->data['parentPageRow']['uid']);

        $resultArray['html'] = \sprintf(
            '<div class="formengine-field-item t3js-formengine-field-item">%s</div>',
            \implode('<br>', $this->links)
        );

        return $resultArray;
    }

    private function buildLinkForSchemaOrg(): void
    {
        $this->links[] = $this->buildLink(
            'LLL:EXT:schema_records/Resources/Private/Language/TypeLinks.xlf:schema_org',
            'https://schema.org/' . $this->selectedType
        );
    }

    private function buildLink(string $description, string $link): string
    {
        if (!\filter_var($link, \FILTER_VALIDATE_URL) || !\str_starts_with($link, 'http')) {
            throw new \DomainException(
                \sprintf(
                    'The given link "%s" for schema type "%s" is not a valid web URL',
                    $link,
                    $this->selectedType
                ),
                1584559568
            );
        }

        return \sprintf(
            '%s <a href="%s" style="text-decoration:underline" target="_blank" rel="noopener noreferrer">%s</a>',
            \htmlspecialchars($this->localise($description)),
            $link,
            $link
        );
    }

    private function localise(string $value): string
    {
        if (!\str_starts_with($value, 'LLL:')) {
            return $value;
        }

        return $this->getLanguageService()->sL($value);
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    private function buildLinksFromTSConfig(int $pageUid): void
    {
        $pageTS = BackendUtility::getPagesTSconfig($pageUid);
        $links = GeneralUtility::removeDotsFromTS(
            $pageTS['tx_schemarecords.']['info.']['types.'][$this->selectedType . '.'] ?? []
        );

        foreach ($links as $link) {
            if (!isset($link['link'])) {
                continue;
            }

            $this->links[] = $this->buildLink($link['description'] ?? '', $link['link']);
        }
    }
}
