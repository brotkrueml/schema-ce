<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Middleware;

use Brotkrueml\Schema\Manager\SchemaManager;
use Brotkrueml\SchemaRecords\Domain\Model\Property;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use Brotkrueml\SchemaRecords\Domain\Repository\TypeRepository;
use Brotkrueml\SchemaRecords\Middleware\TypeEmbedding;
use Brotkrueml\SchemaRecords\Tests\Unit\Helper\LogManagerMockTrait;
use Brotkrueml\SchemaRecords\Tests\Unit\Helper\TypeFixtureNamespaceTrait;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Http\RequestHandler;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TypeEmbeddingTest extends UnitTestCase
{
    use LogManagerMockTrait;
    use TypeFixtureNamespaceTrait;

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
     * @var MockObject|RequestHandler
     */
    protected $requestHandlerMock;

    /**
     * @var TypeEmbedding
     */
    protected $subject;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::setTypeNamespaceToFixtureNamespace();
    }

    public static function tearDownAfterClass(): void
    {
        static::restoreOriginalTypeNamespace();
        parent::tearDownAfterClass();
    }

    protected function initialiseGeneralMocks(): void
    {
        $this->initialiseLogManagerMock();

        $this->schemaManager = new SchemaManager();

        $this->controllerMock = $this->createMock(TypoScriptFrontendController::class);

        $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->onlyMethods(['get'])
            ->getMock();

        $this->typeRepositoryMock = $this->getMockBuilder(TypeRepository::class)
            ->setConstructorArgs([$this->objectManagerMock])
            ->onlyMethods(['createQuery', 'findAll'])
            ->getMock();

        $this->objectManagerMock
            ->expects($this->once())
            ->method('get')
            ->with(TypeRepository::class)
            ->willReturn($this->typeRepositoryMock);

        $querySettingsMock = $this->getMockBuilder(Typo3QuerySettings::class)
            ->onlyMethods(['setStoragePageIds'])
            ->getMock();

        $querySettingsMock
            ->expects($this->once())
            ->method('setStoragePageIds');

        $queryMock = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQuerySettings'])
            ->getMock();

        $queryMock
            ->expects($this->once())
            ->method('getQuerySettings')
            ->willReturn($querySettingsMock);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $this->subject = new TypeEmbedding(
            $this->controllerMock,
            $this->objectManagerMock,
            $this->schemaManager
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
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routing')
            ->willReturn($pageArguments);

        $this->requestHandlerMock = $this->getMockBuilder(RequestHandler::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $this->requestHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($this->serverRequestMock);
    }

    /**
     * @test
     */
    public function constructWorksCorrectlyWithNoParametersGiven(): void
    {
        $GLOBALS['TSFE'] = 'fake controller';

        /** @noinspection PhpUnhandledExceptionInspection */
        $reflector = new \ReflectionClass(TypeEmbedding::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $controller = $reflector->getProperty('controller');
        $controller->setAccessible(true);

        /** @noinspection PhpUnhandledExceptionInspection */
        $objectManager = $reflector->getProperty('objectManager');
        $objectManager->setAccessible(true);

        /** @noinspection PhpUnhandledExceptionInspection */
        $schemaManager = $reflector->getProperty('schemaManager');
        $schemaManager->setAccessible(true);

        $subject = new TypeEmbedding();

        $this->assertSame('fake controller', $controller->getValue($subject));
        $this->assertInstanceOf(SchemaManager::class, $schemaManager->getValue($subject));
        $this->assertInstanceOf(ObjectManager::class, $objectManager->getValue($subject));

        unset($GLOBALS['TSFE']);
    }

    /**
     * @test
     */
    public function processDoesNotEmbedSchemaWhenThereAreNoTypesDefined(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @covers \Brotkrueml\SchemaRecords\Middleware\TypeEmbedding::process
     */
    public function processEmbedsEmptyTypeCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithSingleValuePropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setName('name');
        $property->setSingleValue('some single value');
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","name":"some single value"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithUrlPropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseContentObjectRendererMock();
        $this->initialiseRequestMocks();

        $this->contentObjectRendererMock
            ->expects($this->once())
            ->method('typoLink_URL')
            ->with(['parameter' => 'http://example.org/', 'forceAbsoluteUrl' => 1])
            ->willReturn('http://example.org/');

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setName('url');
        $property->setVariant(Property::VARIANT_URL);
        $property->setUrl('http://example.org/');
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","url":"http://example.org/"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithBooleanPropertySetToTrueCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_BOOLEAN);
        $property->setName('flag');
        $property->setFlag(true);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","flag":"http://schema.org/True"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithBooleanPropertySetToFalseCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_BOOLEAN);
        $property->setName('flag');
        $property->setFlag(false);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","flag":"http://schema.org/False"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithImagePropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();
        $this->initialiseImageServiceMock();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        /** @var MockObject|File $fileMock */
        $fileMock = $this->createMock(File::class);

        /** @var MockObject|FileReference $fileReferenceMock */
        $fileReferenceMock = $this->getMockBuilder(FileReference::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOriginalResource'])
            ->getMock();

        $fileReferenceMock
            ->expects($this->once())
            ->method('getOriginalResource')
            ->willReturn($fileMock);

        $property = new Property();
        $property->setVariant(Property::VARIANT_IMAGE);
        $property->setName('image');
        $property->addImage($fileReferenceMock);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->imageServiceMock
            ->expects($this->once())
            ->method('getImageUri')
            ->with($fileMock, true)
            ->willReturn('http://example.org/image.png');

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","image":"http://example.org/image.png"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithDateTimePropertyCorrectly(): void
    {
        $originalTimeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Berlin');

        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_DATETIME);
        $property->setName('date');
        $property->setTimestamp(1564245941);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","date":"2019-07-27T18:45:41+02:00"}</script>', $actual);

        date_default_timezone_set($originalTimeZone);
    }

    /**
     * @test
     */
    public function processEmbedsTypeWithDatePropertyCorrectly(): void
    {
        $this->initialiseGeneralMocks();
        $this->initialiseRequestMocks();

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setVariant(Property::VARIANT_DATE);
        $property->setName('date');
        $property->setTimestamp(1564245941);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($this->serverRequestMock, $this->requestHandlerMock);

        $actual = $this->schemaManager->renderJsonLd();

        $this->assertSame('<script type="application/ld+json">{"@context":"http://schema.org","@type":"FixtureThing","date":"2019-07-27"}</script>', $actual);
    }

    /**
     * @test
     */
    public function processThrowsExceptionOnInvalidVariant(): void
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
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routing')
            ->willReturn($pageArguments);

        $requestHandlerMock = $this->createMock(RequestHandler::class);

        $type = new Type();
        $type->_setProperty('uid', 21);
        $type->setSchemaType('FixtureThing');

        $property = new Property();
        $property->setVariant(123456789);
        $property->setName('date');
        $property->setTimestamp(1564245941);
        $type->addProperty($property);

        $this->typeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$type]);

        $this->subject->process($serverRequestMock, $requestHandlerMock);
    }
}
