<?php
declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords;

/**
 * @internal
 */
final class Extension
{
    public const KEY = 'schema_records';

    public const LANGUAGE_PATH_DATABASE = 'LLL:EXT:' . self::KEY . '/Resources/Private/Language/locallang_db.xlf';
}
