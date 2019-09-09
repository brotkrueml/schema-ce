<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Helper;

use Brotkrueml\Schema\Utility\Utility;
use Brotkrueml\SchemaRecords\Tests\Fixtures\Paths;

trait TypeFixtureNamespaceTrait
{
    /** @var string */
    private static $originalNamespace;

    public static function setTypeNamespaceToFixtureNamespace(): void
    {
        /** @noinspection PhpInternalEntityUsedInspection */
        static::$originalNamespace = Utility::setNamespaceForTypes(Paths::TYPE_PATH);
    }

    public static function restoreOriginalTypeNamespace(): void
    {
        /** @noinspection PhpInternalEntityUsedInspection */
        Utility::setNamespaceForTypes(static::$originalNamespace);
    }
}
