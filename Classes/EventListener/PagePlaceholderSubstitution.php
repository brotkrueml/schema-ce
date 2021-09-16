<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\EventListener;

use Brotkrueml\Schema\Model\DataType\Boolean;
use Brotkrueml\SchemaRecords\Event\SubstitutePlaceholderEvent;

final class PagePlaceholderSubstitution
{
    public function __invoke(SubstitutePlaceholderEvent $event): void
    {
        $value = $event->getValue();
        if (!\preg_match('/^{page:(.*?)(\((.*?)\))?}$/', $value, $matches)) {
            return;
        }

        $fieldName = $matches[1];
        $dataType = $matches[3] ?? null;

        if (!\array_key_exists($fieldName, $event->getPageProperties())) {
            return;
        }

        $valueFromPageProperties = $event->getPageProperties()[$fieldName];

        if ($dataType === 'bool') {
            $event->setValue($valueFromPageProperties ? Boolean::TRUE : Boolean::FALSE);
            return;
        }

        if ($dataType === 'date') {
            $event->setValue($this->formatDate('Y-m-d', $valueFromPageProperties));
            return;
        }

        if ($dataType === 'datetime') {
            $event->setValue($this->formatDate('c', $valueFromPageProperties));
            return;
        }

        $event->setValue($valueFromPageProperties);
    }

    private function formatDate(string $format, int $value): ?string
    {
        if ($value === 0) {
            return null;
        }

        return \date($format, $value);
    }
}
