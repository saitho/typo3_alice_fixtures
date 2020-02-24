<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Domain\Model;

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

use TYPO3\CMS\Core\Utility\StringUtility;

final class File implements DataHandlerObjectInterface
{
    use DataHandlerObjectTrait;

    public function getTableName(): string
    {
        return 'sys_file';
    }

    public function getIdentifier()
    {
        return $this->data['identifier'];
    }

    public function getStorage(): int
    {
        return $this->data['storage'] ?? 1;
    }
}
