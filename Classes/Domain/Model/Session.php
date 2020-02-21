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

final class Session implements DataHandlerObjectInterface
{
    use DataHandlerObjectTrait;

    const TABLE_NAME = 'be_sessions';

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getUid()
    {
        if (! isset($this->data['ses_id'])) {
            $this->data['ses_id'] = md5(time() + rand());
        }

        return $this->data['ses_id'];
    }
}
