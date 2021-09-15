<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Service;

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\Schema\Core\Model\TypeInterface;
use Brotkrueml\Schema\Type\TypeRegistry;
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

    /**
     * @var TypeRegistry
     */
    private $typeRegistry;

    public function __construct(ObjectManagerInterface $objectManager = null, TypeRegistry $typeRegistry = null)
    {
        $this->objectManager = $objectManager ?? GeneralUtility::makeInstance(ObjectManager::class);
        $this->typeRegistry = $typeRegistry ?? GeneralUtility::makeInstance(TypeRegistry::class);
    }

    public function getTcaList(array &$configuration): void
    {
        /** @var TypeRepository $typeRepository */
        $typeRepository = $this->objectManager->get(TypeRepository::class);

        $typeModel = $typeRepository->findByIdentifierIgnoringEnableFields(
            (int)$configuration['row']['parent'],
            (int)$configuration['row']['pid']
        );

        if ($typeModel === null) {
            return;
        }

        $typeName = $typeModel->getSchemaType();
        $typeClass = $this->typeRegistry->resolveModelClassFromType($typeName);

        if ($typeClass === null) {
            return;
        }

        /** @var TypeInterface $type */
        $type = new $typeClass();

        foreach ((new PresetsProvider())->getPropertiesForType((int)$configuration['row']['pid'], $type) as $propertyName) {
            $configuration['items'][] = [$propertyName, $propertyName];
        }
    }
}
