<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Event;

class SubstitutePlaceholderEvent
{
    /** @var string */
    private $value;

    /** @var array */
    private $pageProperties;

    public function __construct(string $value, array $pageProperties)
    {
        $this->value = $value;
        $this->pageProperties = $pageProperties;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getPageProperties(): array
    {
        return $this->pageProperties;
    }
}
