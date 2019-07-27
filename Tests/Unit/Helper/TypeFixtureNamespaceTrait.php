<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaCe\Tests\Unit\Helper;

/**
 * This file is part of the "schema_ce" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Brotkrueml\Schema\Utility\Utility;
use Brotkrueml\SchemaCe\Tests\Fixtures\Paths;

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
