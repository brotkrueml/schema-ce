<?php
declare(strict_types=1);

namespace Brotkrueml\SchemaRecords\Tests\Unit\Helper;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait LogManagerMockTrait
{
    protected function initialiseLogManagerMock(): void
    {
        /** @var MockObject|LogManager $logManagerMock */
        $logManagerMock = $this->getMockBuilder(LogManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLogger'])
            ->getMock();

        $logManagerMock
            ->expects($this->any())
            ->method('getLogger')
            ->willReturn(new NullLogger());

        GeneralUtility::setSingletonInstance(LogManager::class, $logManagerMock);
    }
}
