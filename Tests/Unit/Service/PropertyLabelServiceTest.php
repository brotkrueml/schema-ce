<?php

declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Tests\Unit\Service;

use Brotkrueml\SchemaRecords\Extension;
use Brotkrueml\SchemaRecords\Service\PropertyLabelService;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Localization\LanguageService;

class PropertyLabelServiceTest extends TestCase
{
    /**
     * @var PropertyLabelService
     */
    protected $subject;

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
            ->onlyMethods(['sL'])
            ->getMock();

        $languageServiceMock
            ->expects(self::once())
            ->method('sL')
            ->with(Extension::LANGUAGE_PATH_DATABASE . ':tx_schemarecords_domain_model_property.variant.3')
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
        $expected['title'] = '<strong>Some name</strong> (localised value for variant)';

        $this->subject->getLabel($actual);

        self::assertSame($expected, $actual);

        $GLOBALS['LANG'] = $originalLang;
    }
}
