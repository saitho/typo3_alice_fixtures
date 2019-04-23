<?php

namespace Ssch\Typo3AliceFixtures\Tests\Unit\Integration;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ssch\Typo3AliceFixtures\Integration\ExtensionManagementUtilityInterface;
use Ssch\Typo3AliceFixtures\Integration\ExtensionNotFoundException;
use Ssch\Typo3AliceFixtures\Integration\ExtensionResolver;
use Ssch\Typo3AliceFixtures\Integration\ExtensionResolverInterface;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class ExtensionResolverTest extends UnitTestCase
{

    /**
     * @var ExtensionResolverInterface
     */
    protected $subject;

    /**
     * @var ExtensionManagementUtilityInterface|MockObject
     */
    private $extensionManagementUtility;

    protected function setUp()
    {
        $this->extensionManagementUtility = $this->getMockBuilder(ExtensionManagementUtilityInterface::class)->getMock();
        $this->subject = new ExtensionResolver($this->extensionManagementUtility);
    }

    /**
     * @test
     */
    public function missingExtensionThrowsException(): void
    {
        $this->expectException(ExtensionNotFoundException::class);
        $this->extensionManagementUtility->method('getLoadedExtensionList')->willReturn(['foo']);
        $this->subject->resolveExtensions(['bar']);
    }

    /**
     * @test
     */
    public function successfullyResolveExtensions(): void
    {
        $this->extensionManagementUtility->method('getLoadedExtensionList')->willReturn(['bar']);
        $extensions = $this->subject->resolveExtensions(['bar', 'bar']);
        $this->assertCount(1, $extensions);
    }
}
