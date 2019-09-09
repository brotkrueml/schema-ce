<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Service;

use Brotkrueml\SchemaRecords\Service\PropertyLabelService;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Lang\LanguageService;

class PropertyLabelServiceTest extends TestCase
{
    /**
     * @var PropertyLabelService
     */
    protected $subject = null;

    protected function setUp(): void
    {
        $this->subject = new PropertyLabelService();
    }

    /**
     * @test
     */
    public function getLabelReturnsCorrectLabel(): void
    {
        $languageServiceMock = $this->getMockBuilder(LanguageService::class)
            ->disableOriginalConstructor()
            ->setMethods(['sL'])
            ->getMock();

        $languageServiceMock
            ->expects($this->once())
            ->method('sL')
            ->with('LLL:EXT:schema_records/Resources/Private/Language/locallang_db.xlf:tx_schemarecords_domain_model_property.variant.3')
            ->willReturn('localised value for variant');

        $originalLang = $GLOBALS['LANG'] = $languageServiceMock;

        $actual = [
            'title' => '',
            'row' => [
                'name' => ['Some name'],
                'variant' => [3],
            ],
        ];

        $expected = $actual;
        $expected['title'] = 'Some name (localised value for variant)';

        $this->subject->getLabel($actual);

        $this->assertSame($expected, $actual);

        $GLOBALS['LANG'] = $originalLang;
    }
}
