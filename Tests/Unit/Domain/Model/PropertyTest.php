<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Domain\Model;

use Brotkrueml\SchemaRecords\Domain\Model\Property;
use Brotkrueml\SchemaRecords\Domain\Model\Type;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PropertyTest extends UnitTestCase
{
    /**
     * @var Property
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Property();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getVariantReturnsInitialValue(): void
    {
        self::assertSame(0, $this->subject->getVariant());
    }

    /**
     * @test
     */
    public function setVariantAndGetVariant(): void
    {
        $this->subject->setVariant(12);

        $this->assertSame(12, $this->subject->getVariant());
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValue(): void
    {
        $this->assertSame('', $this->subject->getName());
    }

    /**
     * @test
     */
    public function setNameAndGetName(): void
    {
        $this->subject->setName('Some name');

        $this->assertSame('Some name', $this->subject->getName());
    }

    /**
     * @test
     */
    public function getSingleValueReturnsInitialValue(): void
    {
        $this->assertSame('', $this->subject->getSingleValue());
    }

    /**
     * @test
     */
    public function setSingleValueAndGetSingleValue(): void
    {
        $this->subject->setSingleValue('Some single value');

        $this->assertSame('Some single value', $this->subject->getSingleValue());
    }

    /**
     * @test
     */
    public function getUrlReturnsInitialValue(): void
    {
        $this->assertSame('', $this->subject->getUrl());
    }

    /**
     * @test
     */
    public function setUrlAndGetUrl(): void
    {
        $this->subject->setUrl('Some url');

        $this->assertSame('Some url', $this->subject->getUrl());
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValue(): void
    {
        $this->assertInstanceOf(ObjectStorage::class, $this->subject->getImages());
        $this->assertSame(0, $this->subject->getImages()->count());
    }

    /**
     * @test
     */
    public function setImageAndGetImage(): void
    {
        $fileReference = new FileReference();
        $objectStorageHoldingExactlyOneImage = new ObjectStorage();
        $objectStorageHoldingExactlyOneImage->attach($fileReference);

        $this->subject->setImages($objectStorageHoldingExactlyOneImage);

        $this->assertSame($objectStorageHoldingExactlyOneImage, $this->subject->getImages());
    }

    /**
     * @test
     */
    public function addImageToObjectStorageHoldingImages()
    {
        $fileReference = new FileReference();
        $imagesObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $imagesObjectStorageMock
            ->expects($this->once())
            ->method('attach')
            ->with($this->equalTo($fileReference));

        $this->inject($this->subject, 'images', $imagesObjectStorageMock);

        $this->subject->addImage($fileReference);
    }

    /**
     * @test
     */
    public function removeImageFromObjectStorageHoldingImages()
    {
        $fileReference = new FileReference();
        $imagesObjectStorageMock = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $imagesObjectStorageMock
            ->expects($this->once())
            ->method('detach')
            ->with($this->equalTo($fileReference));

        $this->inject($this->subject, 'images', $imagesObjectStorageMock);

        $this->subject->removeImage($fileReference);
    }

    /**
     * @test
     */
    public function getFlagReturnsInitialValue(): void
    {
        $this->assertFalse($this->subject->getFlag());
    }

    /**
     * @test
     */
    public function setFlagAndGetFlag(): void
    {
        $this->subject->setFlag(true);

        $this->assertTrue($this->subject->getFlag());
    }

    /**
     * @test
     */
    public function getTypeReferenceReturnsInitialValue(): void
    {
        $this->assertNull($this->subject->getTypeReference());
    }

    /**
     * @test
     */
    public function setTypeReferenceForTypeSetsTypeReference(): void
    {
        $schemaTypeFixture = new Type();
        $this->subject->setTypeReference($schemaTypeFixture);

        $this->assertSame($schemaTypeFixture, $this->subject->getTypeReference());
    }

    /**
     * @test
     */
    public function getReferenceOnlyReturnsInitialValue(): void
    {
        $this->assertFalse($this->subject->getReferenceOnly());
    }

    /**
     * @test
     */
    public function setReferenceOnlyAndGetReferenceOnly(): void
    {
        $this->subject->setReferenceOnly(true);

        $this->assertTrue($this->subject->getReferenceOnly());
    }

    /**
     * @test
     */
    public function getTimestampReturnsInitialValue(): void
    {
        $this->assertSame(0, $this->subject->getTimestamp());
    }

    /**
     * @test
     */
    public function setTimestampAndGetTimestamp(): void
    {
        $this->subject->setTimestamp(1563906067);

        $this->assertSame(1563906067, $this->subject->getTimestamp());
    }
}
