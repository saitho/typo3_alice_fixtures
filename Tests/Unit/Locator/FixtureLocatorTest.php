<?php

namespace Ssch\Typo3AliceFixtures\Tests\Unit\Locator;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\Typo3AliceFixtures\Integration\ExtensionInterface;
use Ssch\Typo3AliceFixtures\Locator\FixtureLocator;

class FixtureLocatorTest extends UnitTestCase
{
    /**
     * @var FixtureLocator
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new FixtureLocator(__DIR__);
    }

    /**
     * @test
     */
    public function locateFilesWithoutExtensions(): void
    {
        $fixtureFiles = $this->subject->locateFiles();
        $this->assertSame([
            __DIR__ . '/fixtures/001-fixtures.yaml',
            __DIR__ . '/fixtures/002-fixtures.yml',
        ], $fixtureFiles);
    }

    /**
     * @test
     */
    public function locateFilesWithExtensions(): void
    {
        $extension = $this->getMockBuilder(ExtensionInterface::class)->getMock();
        $extension->method('getName')->willReturn('extensions');
        $extension->method('getPath')->willReturn(__DIR__ . '/Extensions/');

        $fixtureFiles = $this->subject->locateFiles([$extension]);

        $this->assertSame([
            __DIR__ . '/fixtures/001-fixtures.yaml',
            __DIR__ . '/fixtures/002-fixtures.yml',
            __DIR__ . '/Extensions/fixtures/001-fixtures.yaml',
        ], $fixtureFiles);
    }
}
