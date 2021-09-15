<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Tests\Unit\Service;

use Brotkrueml\Schema\Type\TypeRegistry;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use Brotkrueml\SchemaRecords\Domain\Repository\TypeRepository;
use Brotkrueml\SchemaRecords\Service\PropertyListService;
use Brotkrueml\SchemaRecords\Tests\Helper\SchemaCacheTrait;
use Brotkrueml\SchemaRecords\Tests\Unit\Helper\LogManagerMockTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class PropertyListServiceTest extends TestCase
{
    use LogManagerMockTrait;
    use SchemaCacheTrait;

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

    /**
     * @var Stub|TypeRegistry
     */
    private $typeRegistryStub;

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
            ->onlyMethods(['findByIdentifierIgnoringEnableFields'])
            ->getMock();

        $this->objectManagerMock
            ->expects(self::once())
            ->method('get')
            ->with(TypeRepository::class)
            ->willReturn($this->typeRepositoryMock);

        $this->typeMock = $this->getMockBuilder(Type::class)
            ->onlyMethods(['getSchemaType'])
            ->getMock();

        $this->typeRegistryStub = $this->createStub(TypeRegistry::class);

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
            ->method('findByIdentifierIgnoringEnableFields')
            ->with(42)
            ->willReturn($this->typeMock);

        $this->typeRegistryStub
            ->method('resolveModelClassFromType')
            ->with('TypeDoesNotExist')
            ->willReturn(null);

        $subject = new PropertyListService($this->objectManagerMock, $this->typeRegistryStub);

        $subject->getTcaList($configuration);

        self::assertSame([['', '']], $configuration['items']);
    }
}
