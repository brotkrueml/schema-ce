<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Slots;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\Schema\Model\DataType\Boolean;
use Brotkrueml\SchemaRecords\Event\SubstitutePlaceholderEvent;

final class PagePlaceholderSubstitutionSlot
{
    public function substitute(SubstitutePlaceholderEvent $event): void
    {
        $value = $event->getValue();
        $pageProperties = $event->getPageProperties();

        if (!\preg_match('/^{page:(.*?)(\((.*?)\))?}$/', $value, $matches)) {
            return;
        }

        $fieldName = $matches[1];
        $dataType = $matches[3] ?? null;

        if (!isset($pageProperties[$fieldName])) {
            return;
        }

        if ($dataType === 'bool') {
            $event->setValue($pageProperties[$fieldName] ? Boolean::TRUE : Boolean::FALSE);
            return;
        }

        if ($dataType === 'date') {
            $event->setValue($this->formatDate('Y-m-d', $pageProperties[$fieldName]));
            return;
        }

        if ($dataType === 'datetime') {
            $event->setValue($this->formatDate('c', $pageProperties[$fieldName]));
            return;
        }

        $event->setValue($pageProperties[$fieldName]);
    }

    private function formatDate(string $format, int $value): ?string
    {
        if ($value === 0) {
            return null;
        }

        return \date($format, $value);
    }
}
