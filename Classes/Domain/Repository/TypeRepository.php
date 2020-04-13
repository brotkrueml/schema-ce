<?php
namespace Brotkrueml\SchemaRecords\Domain\Repository;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
}
