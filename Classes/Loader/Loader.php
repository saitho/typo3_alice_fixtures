<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Loader;

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

use Fidry\AliceDataFixtures\LoaderInterface as AliceDataFixturesLoaderInterface;
use Fidry\AliceDataFixtures\Persistence\PersisterAwareInterface;
use Ssch\Typo3AliceFixtures\Integration\ExtensionResolverInterface;
use Ssch\Typo3AliceFixtures\Locator\FixtureLocatorInterface;

final class Loader implements LoaderInterface
{
    /**
     * @var FixtureLocatorInterface
     */
    private $fixtureLocator;

    /**
     * @var AliceDataFixturesLoaderInterface|PersisterAwareInterface
     */
    private $purgeLoader;

    /**
     * @var AliceDataFixturesLoaderInterface|PersisterAwareInterface
     */
    private $appendLoader;

    /**
     * @var ExtensionResolverInterface
     */
    private $extensionResolver;

    public function __construct(FixtureLocatorInterface $fixtureLocator, PurgeLoaderFactory $purgeLoaderFactory, AppendLoaderFactory $appendLoaderFactory, ExtensionResolverInterface $extensionResolver)
    {
        $this->fixtureLocator = $fixtureLocator;
        $this->purgeLoader = $purgeLoaderFactory->createLoader();
        $this->appendLoader = $appendLoaderFactory->createLoader();
        $this->extensionResolver = $extensionResolver;
    }

    public function load(array $extensions, bool $append): array
    {
        $extensions = $this->extensionResolver->resolveExtensions($extensions);
        $fixtureFiles = $this->fixtureLocator->locateFiles($extensions);

        $loader = $append ? $this->appendLoader : $this->purgeLoader;

        return $this->loadFixtures($loader, $fixtureFiles);
    }

    /**
     * @param AliceDataFixturesLoaderInterface|PersisterAwareInterface $loader
     * @param string[] $files
     *
     * @return object[]
     */
    protected function loadFixtures(AliceDataFixturesLoaderInterface $loader, array $files): array
    {
        return $loader->load($files, [], []);
    }
}
