<?php

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Property extends AbstractEntity
{
    public const VARIANT_SINGLE_VALUE = 0;
    public const VARIANT_URL = 1;
    public const VARIANT_IMAGE = 2;
    public const VARIANT_BOOLEAN = 3;
    public const VARIANT_TYPE_REFERENCE = 4;
    public const VARIANT_DATETIME = 5;
    public const VARIANT_DATE = 6;

    /**
     * variant
     *
     * @var int
     */
    protected $variant = 0;

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * singleValue
     *
     * @var string
     */
    protected $singleValue = '';

    /**
     * url
     *
     * @var string
     */
    protected $url = '';

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $image;

    /**
     * flag
     *
     * @var bool
     */
    protected $flag = false;

    /**
     * typeReference
     *
     * @var \Brotkrueml\SchemaRecords\Domain\Model\Type
     */
    protected $typeReference;

    /**
     * referenceOnly
     *
     * @var bool
     */
    protected $referenceOnly = false;

    /**
     * timestamp
     *
     * @var int
     */
    protected $timestamp = 0;

    /**
     * Returns the variant
     *
     * @return int $variant
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Sets the variant
     *
     * @param int $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the singleValue
     *
     * @return string $singleValue
     */
    public function getSingleValue()
    {
        return $this->singleValue;
    }

    /**
     * Sets the singleValue
     *
     * @param string $singleValue
     */
    public function setSingleValue($singleValue)
    {
        $this->singleValue = $singleValue;
    }

    /**
     * Returns the url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get the image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }

    /**
     * Get flag
     *
     * @return bool
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set flag
     *
     * @param bool $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * Returns the typeReference
     *
     * @return \Brotkrueml\SchemaRecords\Domain\Model\Type typeReference
     */
    public function getTypeReference()
    {
        return $this->typeReference;
    }

    /**
     * Sets the typeReference
     *
     * @param \Brotkrueml\SchemaRecords\Domain\Model\Type $typeReference
     */
    public function setTypeReference(\Brotkrueml\SchemaRecords\Domain\Model\Type $typeReference)
    {
        $this->typeReference = $typeReference;
    }

    /**
     * Returns referenceOnly
     *
     * @return bool
     */
    public function getReferenceOnly()
    {
        return $this->referenceOnly;
    }

    /**
     * Sets referenceOnly
     *
     * @param bool $referenceOnly
     */
    public function setReferenceOnly($referenceOnly)
    {
        $this->referenceOnly = $referenceOnly;
    }

    /**
     * Get timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set timestamp
     *
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
