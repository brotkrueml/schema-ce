<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Tests\Fixtures\Model\Type;

use Brotkrueml\Schema\Core\Model\AbstractType;

final class Thing extends AbstractType
{
    protected static $propertyNames = [
        'date',
        'description',
        'flag',
        'image',
        'name',
        'url',
    ];
}
