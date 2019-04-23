<?php
declare(strict_types = 1);

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
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use TYPO3\CMS\Core\Core\Environment;

final class FixtureLocator implements FixtureLocatorInterface
{
    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var array
     */
    private $fixturePaths = [];

    public function __construct(string $rootDirectory = null)
    {
        if (! is_string($rootDirectory) || ! is_dir($rootDirectory)) {
            $rootDirectory = Environment::getProjectPath();
        }

        $this->fixturePaths = ['fixtures'];
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * @inheritDoc
     */
    public function locateFiles(array $extensions = []): array
    {
        $fixtureFiles = array_merge(
            ...array_map(
                function (string $rootDir): array {
                    return $this->doLocateFiles($rootDir);
                },
                [$this->rootDirectory]
            ),
            ...array_map(
                function (ExtensionInterface $extension): array {
                    return $this->doLocateFiles($extension->getPath());
                },
                $extensions
            )
        );

        return $fixtureFiles;
    }

    private function doLocateFiles(string $path)
    {
        $fullPaths = array_filter(array_map(static function (string $fixturePath) use ($path): string {
            return sprintf('%s/%s', $path, $fixturePath);
        }, $this->fixturePaths), static function ($fullPath) {
            return $fullPath && file_exists($fullPath);
        });

        if ([] === $fullPaths) {
            return [];
        }

        /** @var Finder|SplFileInfo[] $files */
        $files = Finder::create()->files()->in($fullPaths)->depth(0)->name('/.*\.(ya?ml|php)$/i');

        // this sort helps to set an order with filename
        // ( "001-root-level-fixtures.yml", "002-another-level-fixtures.yml", ... )
        $files = $files->sort(static function (SplFileInfo $a, SplFileInfo $b) {
            return strcasecmp($a->getBasename(), $b->getBasename());
        });

        $fixtureFiles = [];

        foreach ($files as $file) {
            $fixtureFiles[$file->getRealPath()] = true;
        }

        return array_keys($fixtureFiles);
    }
}
