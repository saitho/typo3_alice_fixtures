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

use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Fidry\AliceDataFixtures\Loader\SimpleLoader;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Ssch\Typo3AliceFixtures\Persister\DataHandlerPersister;
use Ssch\Typo3AliceFixtures\Processors\FileProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class AppendLoaderFactory implements LoaderFactoryInterface
{

    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];

    public function __construct(FileProcessor $fileProcessor)
    {
        $this->processors[] = $fileProcessor;
    }

    public function createLoader(): \Fidry\AliceDataFixtures\LoaderInterface
    {
        return new PersisterLoader(
            new SimpleLoader(
                new NativeLoader()
            ),
            self::getObjectManager()->get(DataHandlerPersister::class),
            null,
            $this->processors
        );
    }

    /**
     * @return object|ObjectManager
     */
    private static function getObjectManager(): ObjectManagerInterface
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
