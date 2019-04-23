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

final class Extension implements ExtensionInterface
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function createFromName($name): Extension
    {
        return new static($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return sprintf('%s/Resources/Private/', ExtensionManagementUtility::extPath($this->name));
    }
}
