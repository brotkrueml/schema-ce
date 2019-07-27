<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaCe\Tests\Unit\Domain\Model;

/**
 * This file is part of the "schema_ce" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Brotkrueml\SchemaCe\Domain\Model\Property;
use Brotkrueml\SchemaCe\Domain\Model\Type;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TypeTest extends UnitTestCase
{
    /**
     * @var Type
     */
    protected $subject = null;

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
        $this->assertSame('', $this->subject->getSchemaType());
    }

    /**
     * @test
     */
    public function setSchemaTypeAndGetSchemaType()
    {
        $this->subject->setSchemaType('Some schema type');

        $this->assertSame('Some schema type', $this->subject->getSchemaType());
    }

    /**
     * @test
     */
    public function getSchemaIdReturnsInitialValue()
    {
        $this->assertSame('', $this->subject->getSchemaId());
    }

    /**
     * @test
     */
    public function setSchemaIdAndGetSchemaId()
    {
        $this->subject->setSchemaId('Some schema id');

        $this->assertSame('Some schema id', $this->subject->getSchemaId());
    }

    /**
     * @test
     */
    public function getWebpageMainentityReturnsInitialValue(): void
    {
        $this->assertFalse($this->subject->getWebpageMainentity());
    }

    /**
     * @test
     */
    public function setWebpageMainentityAndGetWebpageMainentity()
    {
        $this->subject->setWebpageMainentity(true);

        $this->assertTrue($this->subject->getWebpageMainentity());
    }

    /**
     * @test
     */
    public function getPropertiesReturnsInitialValue()
    {
        $this->assertInstanceOf(ObjectStorage::class, $this->subject->getProperties());
        $this->assertSame(0, $this->subject->getProperties()->count());
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

        $this->assertSame($objectStorageHoldingExactlyOneProperty, $this->subject->getProperties());
    }

    /**
     * @test
     */
    public function addPropertyToObjectStorageHoldingProperties()
    {
        $property = new Property();
        $propertiesObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesObjectStorageMock
            ->expects($this->once())
            ->method('attach')
            ->with($this->equalTo($property));

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
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesObjectStorageMock
            ->expects($this->once())
            ->method('detach')
            ->with(self::equalTo($property));

        $this->inject($this->subject, 'properties', $propertiesObjectStorageMock);

        $this->subject->removeProperty($property);
    }
}
