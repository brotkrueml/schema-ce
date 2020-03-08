<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Service;

use Brotkrueml\SchemaRecords\Domain\Model\Type;
use Brotkrueml\SchemaRecords\Domain\Repository\TypeRepository;
use Brotkrueml\SchemaRecords\Service\PropertyListService;
use Brotkrueml\SchemaRecords\Tests\Helper\SchemaCacheTrait;
use Brotkrueml\SchemaRecords\Tests\Unit\Helper\LogManagerMockTrait;
use Brotkrueml\SchemaRecords\Tests\Unit\Helper\TypeFixtureNamespaceTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class PropertyListServiceTest extends TestCase
{
    use LogManagerMockTrait;
    use SchemaCacheTrait;
    use TypeFixtureNamespaceTrait;

    /**
     * @var MockObject|ObjectManagerInterface
     */
    private $objectManagerMock;

    /**
     * @var MockObject|TypeRepository
     */
    private $typeRepositoryMock;

    /** @var MockObject|Type $typeMock */
    private $typeMock;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->initialiseLogManagerMock();

        $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $this->typeRepositoryMock = $this->getMockBuilder(TypeRepository::class)
            ->setConstructorArgs([$this->objectManagerMock])
            ->onlyMethods(['findByIdentifier', 'createQuery'])
            ->getMock();

        $querySettingsMock = $this->getMockBuilder(Typo3QuerySettings::class)
            ->onlyMethods(['setIgnoreEnableFields'])
            ->getMock();

        $querySettingsMock
            ->expects(self::once())
            ->method('setIgnoreEnableFields')
            ->with(true);

        $queryMock = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQuerySettings'])
            ->getMock();

        $queryMock
            ->expects(self::once())
            ->method('getQuerySettings')
            ->willReturn($querySettingsMock);

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $this->objectManagerMock
            ->expects(self::once())
            ->method('get')
            ->with(TypeRepository::class)
            ->willReturn($this->typeRepositoryMock);

        $this->typeMock = $this->getMockBuilder(Type::class)
            ->onlyMethods(['getSchemaType'])
            ->getMock();

        $this->defineCacheStubsWhichReturnEmptyEntry();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
    }

//    /**
//     * @test
//     * @covers \Brotkrueml\SchemaRecords\Service\PropertyListService::getTcaList
//     */
//    public function getTcaListReturnPropertiesForGivenType(): void
//    {
//        $configuration = [
//            'items' => [
//                ['', ''],
//            ],
//            'row' => [
//                'pid' => 3,
//                'parent' => 42,
//            ],
//        ];
//
//        $this->typeMock
//            ->expects(self::once())
//            ->method('getSchemaType')
//            ->willReturn('FixtureThing');
//
//        $this->typeRepositoryMock
//            ->expects(self::once())
//            ->method('findByIdentifier')
//            ->with(42)
//            ->willReturn($this->typeMock);
//
//        $subject = new PropertyListService($this->objectManagerMock);
//
//        $subject->getTcaList($configuration);
//
//        $expectedItems = [
//            ['', ''],
//            ['date', 'date'],
//            ['description', 'description'],
//            ['flag', 'flag'],
//            ['image', 'image'],
//            ['name', 'name'],
//            ['url', 'url'],
//        ];
//
//        self::assertSame($expectedItems, $configuration['items']);
//    }

    /**
     * @test
     * @covers \Brotkrueml\SchemaRecords\Service\PropertyListService::getTcaList
     */
    public function getTcaListReturnNoPropertiesForUnknownType(): void
    {
        $configuration = [
            'items' => [
                ['', ''],
            ],
            'row' => [
                'parent' => 42,
            ],
        ];

        $this->typeMock
            ->expects(self::once())
            ->method('getSchemaType')
            ->willReturn('TypeDoesNotExist');

        $this->typeRepositoryMock
            ->expects(self::once())
            ->method('findByIdentifier')
            ->with(42)
            ->willReturn($this->typeMock);

        $subject = new PropertyListService($this->objectManagerMock);

        $subject->getTcaList($configuration);

        self::assertSame([['', '']], $configuration['items']);
    }
}
