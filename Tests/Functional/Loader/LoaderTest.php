<?php

namespace Ssch\Typo3AliceFixtures\Tests\Functional\Loader;

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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ssch\Typo3AliceFixtures\Loader\Loader;
use Ssch\Typo3AliceFixtures\Locator\FixtureLocator;
use Ssch\Typo3AliceFixtures\Locator\FixtureLocatorInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extensionmanager\Utility\UpdateScriptUtility;

class LoaderTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/typo3_alice_fixtures'];

    /**
     * @var Loader
     */
    protected $subject;

    /**
     * @var FixtureLocatorInterface|MockObject
     */
    private $fixtureLocator;

    protected function setUp()
    {
        parent::setUp();

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->fixtureLocator = new FixtureLocator(__DIR__ . '/../Fixtures');
        $this->subject = $objectManager->get(Loader::class, $this->fixtureLocator);
    }

    /**
     * @test
     */
    public function loadWithTruncate(): void
    {
        $this->subject->load([], false);

        $this->assertEquals(3, $this->getDatabaseConnection()->selectCount('*', 'tt_content'));
    }
}
