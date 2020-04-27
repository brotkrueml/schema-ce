<?php
declare(strict_types=1);

/*
 * This file is part of the "schema_records" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaRecords\Tests\Unit\Slots;

use Brotkrueml\Schema\Model\DataType\Boolean;
use Brotkrueml\SchemaRecords\Event\SubstitutePlaceholderEvent;
use Brotkrueml\SchemaRecords\Slots\PagePlaceholderSubstitutionSlot;
use PHPUnit\Framework\TestCase;

class PagePlaceholderSubstitutionSlotTest extends TestCase
{
    /** @var PagePlaceholderSubstitutionSlot */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new PagePlaceholderSubstitutionSlot();
    }

    /**
     * @test
     * @dataProvider dataProvider
     *
     * @param string $value
     * @param array $pageProperties
     * @param string|null $expected
     */
    public function valueIsCorrectFormatted(string $value, array $pageProperties, ?string $expected)
    {
        $event = new SubstitutePlaceholderEvent($value, $pageProperties);
        $this->subject->substitute($event);

        self::assertSame($expected, $event->getValue());
    }

    public function dataProvider(): array
    {
        return [
            'Plain value is not touched' => [
                'This is a plain value',
                [],
                'This is a plain value',
            ],
            'Page property without formatter is substituted' => [
                '{page:title}',
                ['title' => 'This is some page title'],
                'This is some page title',
            ],
            'Page property with boolean formatter evaluates to true' => [
                '{page:nav_title(bool)}',
                ['nav_title' => 1],
                Boolean::TRUE,
            ],
            'Page property with boolean formatter evaluates to false' => [
                '{page:nav_title(bool)}',
                ['nav_title' => 0],
                Boolean::FALSE,
            ],
            'Timestamp page property with date formatter is substituted' => [
                '{page:lastUpdated(date)}',
                ['lastUpdated' => 1569256359],
                '2019-09-23',
            ],
            'Timestamp page property with datetime formatter is substituted' => [
                '{page:lastUpdated(datetime)}',
                ['lastUpdated' => 1569256359],
                date('c', 1569256359),
            ],
            'Empty timestamp page property with date formatter is null' => [
                '{page:lastUpdated(date)}',
                ['lastUpdated' => 0],
                null,
            ],
            'Empty timestamp page property with datetime formatter is null' => [
                '{page:lastUpdated(datetime)}',
                ['lastUpdated' => 0],
                null,
            ],
            'Not available page property is left untouched' => [
                '{page:not_available}',
                [],
                '{page:not_available}',
            ],
            'Page property with null value is set to null' => [
                '{page:description}',
                ['description' => null],
                null,
            ],
        ];
    }
}
