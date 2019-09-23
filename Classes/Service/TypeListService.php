<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\Schema\Provider\TypesProvider;

final class TypeListService
{
    public function getTcaList(array $config): array
    {
        $types = (new TypesProvider())->getContentTypes();

        \array_walk($types, function (&$type) {
            $type = [$type, $type];
        });

        $config['items'] = \array_merge($config['items'], $types);

        return $config;
    }
}
