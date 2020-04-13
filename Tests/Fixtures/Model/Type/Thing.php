<?php
declare(strict_types=1);

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
