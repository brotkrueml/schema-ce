<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\EventListener;

use Brotkrueml\Schema\Core\Model\TypeInterface;
use Brotkrueml\Schema\Event\RenderAdditionalTypesEvent;
use Brotkrueml\Schema\Manager\SchemaManager;
use Brotkrueml\Schema\Type\TypeFactory;
use Brotkrueml\SchemaRecords\Domain\Model\Property;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use Brotkrueml\SchemaRecords\Domain\Repository\TypeRepository;
use Brotkrueml\SchemaRecords\Event\SubstitutePlaceholderEvent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @internal
 */
final class AddRecords implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const MAX_NESTED_TYPES = 5;
    private const MAX_IMAGE_HEIGHT = '1200m';
    private const MAX_IMAGE_WIDTH = '1200m';

    private ContentObjectRenderer $contentObjectRenderer;
    private EventDispatcher $eventDispatcher;
    private ImageService $imageService;
    private SchemaManager $schemaManager;
    private TypeRepository $typeRepository;
    private TypoScriptFrontendController $typoScriptFrontendController;
    private array $referencedRecords;
    private array $processedRecords;
    // Counting the nested types, this can also be a hint of a type loop!
    private int $nestedTypesCounter;

    public function __construct(
        ContentObjectRenderer $contentObjectRenderer,
        EventDispatcher $eventDispatcher,
        ImageService $imageService,
        SchemaManager $schemaManager,
        TypeRepository $typeRepository,
        TypoScriptFrontendController $typoScriptFrontendController
    ) {
        $this->contentObjectRenderer = $contentObjectRenderer;
        $this->eventDispatcher = $eventDispatcher;
        $this->imageService = $imageService;
        $this->schemaManager = $schemaManager;
        $this->typeRepository = $typeRepository;
        $this->typoScriptFrontendController = $typoScriptFrontendController;
    }

    public function __invoke(RenderAdditionalTypesEvent $event): void
    {
        $this->referencedRecords = [];
        $this->processedRecords = [];

        $records = $this->typeRepository->findAllFromPage($this->typoScriptFrontendController->page['uid']);
        foreach ($records as $record) {
            $this->nestedTypesCounter = 0;

            if (\array_key_exists($record->getUid(), $this->referencedRecords)) {
                continue;
            }

            $this->buildType($record, true);
        }

        foreach ($this->processedRecords as $recordUid => $processed) {
            if (\in_array($recordUid, $this->referencedRecords)) {
                continue;
            }

            if ($processed['isWebPageMainEntity']) {
                $this->schemaManager->addMainEntityOfWebPage($processed['type']);
            } else {
                $this->schemaManager->addType($processed['type']);
            }
        }
    }

    private function buildType(Type $record, $isRootType = false, $onlyReference = false): ?TypeInterface
    {
        $this->nestedTypesCounter++;

        if ($this->nestedTypesCounter > static::MAX_NESTED_TYPES) {
            $message = sprintf(
                'Too many nested schema types in page uid "%d", last type "%s" with uid "%d"',
                $this->typoScriptFrontendController->page['uid'],
                $record->getSchemaType(),
                $record->getUid()
            );

            $this->logger->warning($message);

            $this->nestedTypesCounter--;

            return null;
        }

        $typeModel = TypeFactory::createType($record->getSchemaType());

        $id = $record->getSchemaId();
        if (!empty($id)) {
            $typeModel->setId($id);
        }

        if (!$onlyReference || empty($id)) {
            foreach ($record->getProperties() as $property) {
                /** @var Property $property */
                switch ($property->getVariant()) {
                    case Property::VARIANT_SINGLE_VALUE:
                        $typeModel->addProperty(
                            $property->getName(),
                            $this->dispatchSubstitutePlaceholderEvent($property->getSingleValue())
                        );
                        break;

                    case Property::VARIANT_URL:
                        $url = $this->contentObjectRenderer->typoLink_URL([
                            'parameter' => $property->getUrl(),
                            'forceAbsoluteUrl' => 1
                        ]);

                        $typeModel->addProperty($property->getName(), $url);
                        break;

                    case Property::VARIANT_BOOLEAN:
                        $typeModel->setProperty($property->getName(), $property->getFlag());
                        break;

                    case Property::VARIANT_IMAGE:
                        $image = $property->getImage();
                        if (!empty($image)) {
                            $typeModel->addProperty(
                                $property->getName(),
                                $this->cropImage($image->getOriginalResource())
                            );
                        }
                        break;

                    case Property::VARIANT_TYPE_REFERENCE:
                        if ($property->getTypeReference() instanceof Type) {
                            $typeModel->addProperty(
                                $property->getName(),
                                $this->buildType($property->getTypeReference(), false, $property->getReferenceOnly())
                            );
                        }
                        break;

                    case Property::VARIANT_DATETIME:
                        if ($property->getTimestamp()) {
                            $dateTime = (new \DateTime())->setTimestamp($property->getTimestamp());
                            $typeModel->setProperty($property->getName(), $dateTime->format('c'));
                        }
                        break;

                    case Property::VARIANT_DATE:
                        if ($property->getTimestamp()) {
                            $dateTime = (new \DateTime())->setTimestamp($property->getTimestamp());
                            $typeModel->setProperty($property->getName(), $dateTime->format('Y-m-d'));
                        }
                        break;

                    default:
                        throw new \DomainException(
                            sprintf('Variant "%s" for a property is not valid!', $property->getVariant()),
                            1563791267
                        );
                }
            }

            if (!$isRootType) {
                $this->referencedRecords[] = $record->getUid();
            }
        }

        if ($isRootType) {
            $this->processedRecords[$record->getUid()] = [
                'isWebPageMainEntity' => $record->getWebpageMainentity(),
                'type' => $typeModel
            ];

            return null;
        }

        $this->nestedTypesCounter--;

        return $typeModel;
    }

    private function dispatchSubstitutePlaceholderEvent(string $value): ?string
    {
        if (\str_starts_with($value, '{')) {
            $event = new SubstitutePlaceholderEvent($value, $this->typoScriptFrontendController->page);
            $event = $this->eventDispatcher->dispatch($event);
            $value = $event->getValue();
        }

        return $value;
    }

    private function cropImage(FileReference $originalImage): string
    {
        $cropString = $originalImage instanceof FileReference ? $originalImage->getProperty('crop') : '';
        $cropVariantCollection = CropVariantCollection::create((string)$cropString);
        $cropArea = $cropVariantCollection->getCropArea('default');
        $processingInstructions = [
            'width' => self::MAX_IMAGE_WIDTH,
            'height' => self::MAX_IMAGE_HEIGHT,
            'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($originalImage),
        ];

        $processedImage = $this->imageService->applyProcessingInstructions(
            $originalImage,
            $processingInstructions
        );

        return $this->imageService->getImageUri($processedImage, true);
    }
}
