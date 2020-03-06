<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Domain\Model;

use Brotkrueml\SchemaRecords\Domain\Model\Property;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TypeTest extends UnitTestCase
{
    /**
     * @var Type
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Type();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getSchemaTypeReturnsInitialValue()
    {
        self::assertSame('', $this->subject->getSchemaType());
    }

    /**
     * @test
     */
    public function setSchemaTypeAndGetSchemaType()
    {
        $this->subject->setSchemaType('Some schema type');

        self::assertSame('Some schema type', $this->subject->getSchemaType());
    }

    /**
     * @test
     */
    public function getSchemaIdReturnsInitialValue()
    {
        self::assertSame('', $this->subject->getSchemaId());
    }

    /**
     * @test
     */
    public function setSchemaIdAndGetSchemaId()
    {
        $this->subject->setSchemaId('Some schema id');

        self::assertSame('Some schema id', $this->subject->getSchemaId());
    }

    /**
     * @test
     */
    public function getWebpageMainentityReturnsInitialValue(): void
    {
        self::assertFalse($this->subject->getWebpageMainentity());
    }

    /**
     * @test
     */
    public function setWebpageMainentityAndGetWebpageMainentity()
    {
        $this->subject->setWebpageMainentity(true);

        self::assertTrue($this->subject->getWebpageMainentity());
    }

    /**
     * @test
     */
    public function getPropertiesReturnsInitialValue()
    {
        self::assertInstanceOf(ObjectStorage::class, $this->subject->getProperties());
        self::assertSame(0, $this->subject->getProperties()->count());
    }

    /**
     * @test
     */
    public function setPropertiesAndGetProperties()
    {
        $property = new Property();
        $objectStorageHoldingExactlyOneProperty = new ObjectStorage();
        $objectStorageHoldingExactlyOneProperty->attach($property);

        $this->subject->setProperties($objectStorageHoldingExactlyOneProperty);

        self::assertSame($objectStorageHoldingExactlyOneProperty, $this->subject->getProperties());
    }

    /**
     * @test
     */
    public function addPropertyToObjectStorageHoldingProperties()
    {
        $property = new Property();
        $propertiesObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesObjectStorageMock
            ->expects(self::once())
            ->method('attach')
            ->with(self::equalTo($property));

        $this->inject($this->subject, 'properties', $propertiesObjectStorageMock);

        $this->subject->addProperty($property);
    }

    /**
     * @test
     */
    public function removePropertyFromObjectStorageHoldingProperties()
    {
        $property = new Property();
        $propertiesObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesObjectStorageMock
            ->expects(self::once())
            ->method('detach')
            ->with(self::equalTo($property));

        $this->inject($this->subject, 'properties', $propertiesObjectStorageMock);

        $this->subject->removeProperty($property);
    }
}
