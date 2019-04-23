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

use Fidry\AliceDataFixtures\Loader\PurgerLoader;
use Ssch\Typo3AliceFixtures\Persistence\DatabasePurgerFactory;

final class PurgeLoaderFactory implements LoaderFactoryInterface
{
    /**
     * @var \Fidry\AliceDataFixtures\LoaderInterface
     */
    private $loader;

    /**
     * @var DatabasePurgerFactory
     */
    private $databasePurgerFactory;

    public function __construct(AppendLoaderFactory $appendLoaderFactory, DatabasePurgerFactory $databasePurgerFactory)
    {
        $this->loader = $appendLoaderFactory->createLoader();
        $this->databasePurgerFactory = $databasePurgerFactory;
    }

    public function createLoader(): \Fidry\AliceDataFixtures\LoaderInterface
    {
        return new PurgerLoader(
            $this->loader,
            $this->databasePurgerFactory,
            'truncate'
        );
    }
}
