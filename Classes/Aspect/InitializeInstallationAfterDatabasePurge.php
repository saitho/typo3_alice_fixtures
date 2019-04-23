<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Aspect;

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

use Exception;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

final class InitializeInstallationAfterDatabasePurge
{
    /**
     * @var InstallUtility
     */
    private $installUtility;

    public function __construct(InstallUtility $installUtility)
    {
        $this->installUtility = $installUtility;
    }

    public function handle(): void
    {
        try {
            $this->installUtility->install(...ExtensionManagementUtility::getLoadedExtensionListArray());
        } catch (Exception $e) {
        }
    }
}
