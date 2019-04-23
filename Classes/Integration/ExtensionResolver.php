<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Integration;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class ExtensionResolver implements ExtensionResolverInterface
{
    /**
     * @var ExtensionManagementUtilityInterface
     */
    private $extensionManagementUtility;

    /**
     * ExtensionResolver constructor.
     *
     * @param ExtensionManagementUtilityInterface $extensionManagementUtility
     */
    public function __construct(ExtensionManagementUtilityInterface $extensionManagementUtility)
    {
        $this->extensionManagementUtility = $extensionManagementUtility;
    }

    /**
     * @param array $names
     *
     * @return ExtensionInterface[]
     */
    public function resolveExtensions(array $names): array
    {
        $loadedExtensions = $this->extensionManagementUtility->getLoadedExtensionList();

        $result = [];
        foreach ($names as $name) {
            if (! in_array($name, $loadedExtensions, true)) {
                throw ExtensionNotFoundException::create($name, $loadedExtensions);
            }
            $result[$name] = Extension::createFromName($name);
        }

        return array_values($result);
    }
}
