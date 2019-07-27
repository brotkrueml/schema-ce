<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection,@noinspection PhpUnnecessaryFullyQualifiedNameInspection */
namespace Brotkrueml\SchemaCe\Domain\Model;

/**
 * This file is part of the "schema_ce" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class Type extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * schemaType
     *
     * @var string
     */
    protected $schemaType = '';

    /**
     * schemaId
     *
     * @var string
     */
    protected $schemaId = '';

    /**
     * webpageMainentity
     *
     * @var bool
     */
    protected $webpageMainentity = false;

    /**
     * properties
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Brotkrueml\SchemaCe\Domain\Model\Property>
     * @cascade remove
     */
    protected $properties = null;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->properties = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the schemaType
     *
     * @return string $schemaType
     */
    public function getSchemaType()
    {
        return $this->schemaType;
    }

    /**
     * Sets the schemaType
     *
     * @param string $schemaType
     */
    public function setSchemaType($schemaType)
    {
        $this->schemaType = $schemaType;
    }

    /**
     * Returns the schemaId
     *
     * @return string $schemaId
     */
    public function getSchemaId()
    {
        return $this->schemaId;
    }

    /**
     * Sets the schemaId
     *
     * @param string $schemaId
     */
    public function setSchemaId($schemaId)
    {
        $this->schemaId = $schemaId;
    }

    /**
     * Returns the webpageMainEntity
     *
     * @return bool
     */
    public function getWebpageMainentity()
    {
        return $this->webpageMainentity;
    }

    /**
     * Sets the webpageMainEntity
     *
     * @param bool $webpageMainentity
     */
    public function setWebpageMainentity($webpageMainentity)
    {
        $this->webpageMainentity = $webpageMainentity;
    }

    /**
     * Adds a Property
     *
     * @param \Brotkrueml\SchemaCe\Domain\Model\Property $property
     */
    public function addProperty(\Brotkrueml\SchemaCe\Domain\Model\Property $property)
    {
        $this->properties->attach($property);
    }

    /**
     * Removes a Property
     *
     * @param \Brotkrueml\SchemaCe\Domain\Model\Property $propertyToRemove The Property to be removed
     */
    public function removeProperty(\Brotkrueml\SchemaCe\Domain\Model\Property $propertyToRemove)
    {
        $this->properties->detach($propertyToRemove);
    }

    /**
     * Returns the properties
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Brotkrueml\SchemaCe\Domain\Model\Property> $properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Sets the properties
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Brotkrueml\SchemaCe\Domain\Model\Property> $properties
     */
    public function setProperties(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $properties)
    {
        $this->properties = $properties;
    }
}
