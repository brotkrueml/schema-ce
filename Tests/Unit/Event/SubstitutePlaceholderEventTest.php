<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Event;

use Brotkrueml\SchemaRecords\Event\SubstitutePlaceholderEvent;
use PHPUnit\Framework\TestCase;

class SubstitutePlaceholderEventTest extends TestCase
{
    /**
     * @test
     */
    public function getValueReturnsTheValueCorrectly(): void
    {
        $subject = new SubstitutePlaceholderEvent('some value', []);

        self::assertSame('some value', $subject->getValue());
    }

    /**
     * @test
     */
    public function setValueWithStringSetsTheValueCorrectly(): void
    {
        $subject = new SubstitutePlaceholderEvent('some value', []);
        $subject->setValue('some other value');

        self::assertSame('some other value', $subject->getValue());
    }

    /**
     * @test
     */
    public function setValueWithNullSetsTheValueCorrectly(): void
    {
        $subject = new SubstitutePlaceholderEvent('some value', []);
        $subject->setValue(null);

        self::assertNull($subject->getValue());
    }

    /**
     * @test
     */
    public function getPagePropertiesReturnsThePagePropertiesCorrectly(): void
    {
        $subject = new SubstitutePlaceholderEvent('', ['some property' => 'some value']);

        self::assertSame(['some property' => 'some value'], $subject->getPageProperties());
    }
}
