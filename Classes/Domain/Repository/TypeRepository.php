<?php

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Domain\Repository;

use Brotkrueml\SchemaRecords\Domain\Model\Type;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class TypeRepository extends Repository
{
    public function findAllFromPage(int $pageId)
    {
        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings();
        $querySettings->setStoragePageIds([$pageId]);
        $querySettings->setLanguageOverlayMode(false);
        $this->setDefaultQuerySettings($querySettings);

        return $this->createQuery()->execute();
    }

    public function findByIdentifierIgnoringEnableFields(int $uid, int $storagePageId): ?Type
    {
        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings();
        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setStoragePageIds([$storagePageId]);
        $query->setQuerySettings($querySettings);

        $query->matching($query->equals('uid', $uid));

        $result = $query->execute();
        if ($result instanceof QueryResultInterface) {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $result->getFirst();
        }

        return null;
    }
}
