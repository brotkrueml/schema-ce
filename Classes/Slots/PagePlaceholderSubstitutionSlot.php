<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Slots;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\SchemaRecords\Enumeration\BoolEnumeration;

final class PagePlaceholderSubstitutionSlot
{
    public function substitute(string &$value, array $pageFields): void
    {
        if (!\preg_match('/^{page:(.*?)(\((.*?)\))?}$/', $value, $matches)) {
            return;
        }

        $fieldName = $matches[1];
        $dataType = $matches[3] ?? null;

        if (!isset($pageFields[$fieldName])) {
            return;
        }

        if ($dataType === 'bool') {
            $value = $pageFields[$fieldName] ? BoolEnumeration::TRUE : BoolEnumeration::FALSE;
            return;
        }

        if ($dataType === 'date') {
            $value = $this->formatDate('Y-m-d', $pageFields[$fieldName]);
            return;
        }

        if ($dataType === 'datetime') {
            $value = $this->formatDate('c', $pageFields[$fieldName]);
            return;
        }

        $value = $pageFields[$fieldName];
    }

    private function formatDate(string $format, int $value): ?string
    {
        if ($value === 0) {
            return null;
        }

        return \date($format, $value);
    }
}
