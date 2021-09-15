<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Tests\Unit\Aspect;

use Brotkrueml\Schema\Manager\SchemaManager;
use Brotkrueml\Schema\Type\TypeRegistry;
use Brotkrueml\SchemaRecords\Aspect\RecordsAspect;
use Brotkrueml\SchemaRecords\Domain\Model\Property;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use Brotkrueml\SchemaRecords\Domain\Repository\TypeRepository;
use Brotkrueml\SchemaRecords\Tests\Fixtures\Model\Type\Thing;
use Brotkrueml\SchemaRecords\Tests\Helper\SchemaCacheTrait;
use Brotkrueml\SchemaRecords\Tests\Unit\Helper\LogManagerMockTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class RecordsAspectTest extends TestCase
{
    use LogManagerMockTrait;
    use SchemaCacheTrait;

    protected $resetSingletonInstances = true;

    /**
     * @var MockObject|ObjectManager
     */
    protected $objectManagerMock;

    /**
     * @var MockObject|TypeRepository
     */
    protected $typeRepositoryMock;

    /**
     * @var MockObject|TypoScriptFrontendController
     */
    protected $controllerMock;

    /**
     * @var SchemaManager
     */
    protected $schemaManager;

    /**
     * @var MockObject|ContentObjectRenderer
     */
    protected $contentObjectRendererMock;

    /**
     * @var MockObject|ImageService
     */
    protected $imageServiceMock;

    /**
     * @var MockObject|ServerRequest
     */
    protected $serverRequestMock;

    /**
     * @var MockObject|Dispatcher
     */
    protected $dispatcherMock;

    /**
     * @var RecordsAspect
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->defineCacheStubsWhichReturnEmptyEntry();

        $typeRegistryStub = $this->createStub(TypeRegistry::class);
        $typeRegistryStub
            ->method('resolveModelClassFromType')
            ->with('Thing')
            ->willReturn(Thing::class);

        GeneralUtility::setSingletonInstance(TypeRegistry::class, $typeRegistryStub);
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        unset($GLOBALS['TYPO3_REQUEST']);
    }

    protected function initialiseGeneralMocks(): void
    {
        $this->initialiseLogManagerMock();

        $this->schemaManager = new SchemaManager();

        $this->controllerMock = $this->createMock(TypoScriptFrontendController::class);

        $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $this->typeRepositoryMock = $this->getMockBuilder(TypeRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findAllFromPage'])
            ->getMock();

        $this->objectManagerMock
            ->expects(self::once())
            ->method('get')
            ->with(TypeRepository::class)
            ->willReturn($this->typeRepositoryMock);

        $this->dispatcherMock = $this->getMockBuilder(Dispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new RecordsAspect(
            $this->controllerMock,
            $this->objectManagerMock,
            $this->schemaManager,
            $this->dispatcherMock
        );
    }

    protected function initialiseContentObjectRendererMock(): void
    {
        $this->contentObjectRendererMock = $this->createMock(ContentObjectRenderer::class);

        GeneralUtility::addInstance(ContentObjectRenderer::class, $this->contentObjectRendererMock);
    }

    protected function initialiseImageServiceMock(): void
    {
        $this->imageServiceMock = $this->createMock(ImageService::class);

        GeneralUtility::setSingletonInstance(ImageService::class, $this->imageServiceMock);
    }

    private function initialiseRequestMocks(): void
    {
        $this->serverRequestMock = $this->getMockBuilder(ServerRequest::class)
            ->onlyMethods(['getAttribute'])
            ->getMock();

        $pageArguments = new PageArguments(42, '0', []);

        $this->serverRequestMock
            ->expects(self::once())
            ->method('getAttribute')
            ->with('routing')
            ->willReturn($pageArguments);

        $GLOBALS['TYPO3_REQUEST'] = $this->serverRequestMock;
    }

    /**
     * @test
     */
    public function executeDoesNotEmbedSchemaWhenThereAreNoTypesDefined(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame('', $actual);
    }

    /**
     * @test
     * @covers \Brotkrueml\SchemaRecords\Aspect\RecordsAspect::execute
     */
    public function executeEmbedsEmptyTypeCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing"}</script>',
            $actual
        );
    }

    /**
     * @test
     */
    public function executeEmbedsTypeWithSingleValuePropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setName('name');
        $property->setSingleValue('some single value');
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","name":"some single value"}</script>',
            $actual
        );
    }

    /**
     * @test
     */
    public function executeEmbedsTypeWithUrlPropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseContentObjectRendererMock();
        $this->initialiseRequestMocks();

        $this->contentObjectRendererMock
            ->expects(self::once())
            ->method('typoLink_URL')
            ->with(['parameter' => 'http://example.org/', 'forceAbsoluteUrl' => 1])
            ->willReturn('http://example.org/');

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setName('url');
        $property->setVariant(Property::VARIANT_URL);
        $property->setUrl('http://example.org/');
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","url":"http://example.org/"}</script>',
            $actual
        );
    }

    /**
     * @test
     */
    public function executeEmbedsTypeWithBooleanPropertySetToTrueCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_BOOLEAN);
        $property->setName('flag');
        $property->setFlag(true);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","flag":"http://schema.org/True"}</script>',
            $actual
        );
    }

    /**
     * @test
     */
    public function executeEmbedsTypeWithBooleanPropertySetToFalseCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_BOOLEAN);
        $property->setName('flag');
        $property->setFlag(false);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","flag":"http://schema.org/False"}</script>',
            $actual
        );
    }

//    /**
//     * @test
//     */
//    public function executeEmbedsTypeWithImagePropertyCorrectly(): void
//    {
//        $this->initialiseGeneralMocks();
//        $this->initialiseRequestMocks();
//        $this->initialiseImageServiceMock();
//
//        $type = new Type();
//        $type->_setProperty('uid', 21);
//        $type->setSchemaType('Thing');
//
//        /** @var MockObject|File $fileMock */
//        $fileMock = $this->createMock(File::class);
//
//        /** @var MockObject|FileReference $fileReferenceMock */
//        $fileReferenceMock = $this->getMockBuilder(FileReference::class)
//            ->disableOriginalConstructor()
//            ->onlyMethods(['getOriginalResource'])
//            ->getMock();
//
//        $fileReferenceMock
//            ->expects(self::once())
//            ->method('getOriginalResource')
//            ->willReturn($fileMock);
//
//        $property = new Property();
//        $property->setVariant(Property::VARIANT_IMAGE);
//        $property->setName('image');
//        $property->addImage($fileReferenceMock);
//        $type->addProperty($property);
//
//        $this->typeRepositoryMock
//            ->expects(self::once())
//            ->method('findAllFromPage')
//            ->willReturn([$type]);
//
//        $this->imageServiceMock
//            ->expects(self::once())
//            ->method('getImageUri')
//            ->with($fileMock, true)
//            ->willReturn('http://example.org/image.png');
//
//        $this->subject->execute($this->schemaManager);
//
//        $actual = $this->schemaManager->renderJsonLd();
//
//        self::assertSame(
//            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","image":"http://example.org/image.png"}</script>',
//            $actual
//        );
//    }

    /**
     * @test
     */
    public function executeEmbedsTypeWithDateTimePropertyCorrectly(): void
    {
        $originalTimeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Berlin');

        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_DATETIME);
        $property->setName('date');
        $property->setTimestamp(1564245941);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","date":"2019-07-27T18:45:41+02:00"}</script>',
            $actual
        );

        date_default_timezone_set($originalTimeZone);
    }

    /**
     * @test
     */
    public function executeEmbedsTypeWithDatePropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_DATE);
        $property->setName('date');
        $property->setTimestamp(1564245941);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);

        $actual = $this->schemaManager->renderJsonLd();

        self::assertSame(
            '<script type="application/ld+json">{"@context":"http://schema.org","@type":"Thing","date":"2019-07-27"}</script>',
            $actual
        );
    }

    /**
     * @test
     */
    public function executeThrowsExceptionOnInvalidVariant(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(1563791267);

        $this->initialiseGeneralMocks();

        /** @var MockObject|ServerRequest $serverRequestMock */
        $serverRequestMock = $this->getMockBuilder(ServerRequest::class)
            ->onlyMethods(['getAttribute'])
            ->getMock();

        $pageArguments = new PageArguments(42, '0', []);

        $serverRequestMock
            ->expects(self::once())
            ->method('getAttribute')
            ->with('routing')
            ->willReturn($pageArguments);

        $GLOBALS['TYPO3_REQUEST'] = $serverRequestMock;

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('Thing');

        $property = new Property();
        $property->setVariant(123456789);
        $property->setName('date');
        $property->setTimestamp(1564245941);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findAllFromPage')
            ->willReturn([$type]);

        $this->subject->execute($this->schemaManager);
    }
}
