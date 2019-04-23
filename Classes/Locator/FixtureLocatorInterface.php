<?php


namespace Ssch\Typo3AliceFixtures\Locator;

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

use Ssch\Typo3AliceFixtures\Integration\ExtensionInterface;

interface FixtureLocatorInterface
{

    /**
     * Locales all the fixture files to load.
     *
     * @param ExtensionInterface[] $extensions
     *
     * @return string[] Fixtures files paths
     */
    public function locateFiles(array $extensions = []): array;
}
