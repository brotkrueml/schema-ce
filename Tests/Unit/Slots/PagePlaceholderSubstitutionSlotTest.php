<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Slots;

use Brotkrueml\SchemaRecords\Enumeration\BoolEnumeration;
use Brotkrueml\SchemaRecords\Slots\PagePlaceholderSubstitutionSlot;
use PHPUnit\Framework\TestCase;

class PagePlaceholderSubstitutionSlotTest extends TestCase
{
    /** @var PagePlaceholderSubstitutionSlot */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new PagePlaceholderSubstitutionSlot();
    }

    /**
     * @test
     * @dataProvider dataProvider
     *
     * @param string $value
     * @param array $pageFields
     * @param string $expected
     */
    public function valueIsCorrectFormatted(string $value, array $pageFields, ?string $expected)
    {
        $this->subject->substitute($value, $pageFields);

        $this->assertSame($expected, $value);
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
                BoolEnumeration::TRUE,
            ],
            'Page property with boolean formatter evaluates to false' => [
                '{page:nav_title(bool)}',
                ['nav_title' => 0],
                BoolEnumeration::FALSE,
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
        ];
    }
}
