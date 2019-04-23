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

interface LoaderInterface
{
    /**
     * Loads the specified fixtures of an application.
     *
     * @param string[] $extensions
     * @param bool $append If true, then the database is not purged before loading the objects
     *
     * @return object[] Loaded objects
     */
    public function load(
        array $extensions,
        bool $append
    ): array;
}
