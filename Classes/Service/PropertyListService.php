<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\Schema\Core\Model\AbstractType;
use Brotkrueml\Schema\Utility\Utility;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use Brotkrueml\SchemaRecords\Domain\Repository\TypeRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class PropertyListService
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager = null)
    {
        $this->objectManager = $objectManager ?? GeneralUtility::makeInstance(ObjectManager::class);
    }

    public function getTcaList(array &$configuration): void
    {
        $typeRepository = $this->objectManager->get(TypeRepository::class);

        $query = $typeRepository->createQuery();
        $querySettings = $query->getQuerySettings();
        $querySettings->setIgnoreEnableFields(true);
        $typeRepository->setDefaultQuerySettings($querySettings);

        /** @var Type $typeModel */
        $typeModel = $typeRepository->findByIdentifier($configuration['row']['parent']);

        if (empty($typeModel)) {
            return;
        }

        $typeName = $typeModel->getSchemaType();
        $typeClass = Utility::getNamespacedClassNameForType($typeName);

        if (!empty($typeClass)) {
            /** @var AbstractType $type */
            $type = new $typeClass();

            foreach ((new PresetsProvider())->getPropertiesForType((int)$configuration['row']['pid'], $type) as $propertyName) {
                $configuration['items'][] = [$propertyName, $propertyName];
            }
        }
    }
}
