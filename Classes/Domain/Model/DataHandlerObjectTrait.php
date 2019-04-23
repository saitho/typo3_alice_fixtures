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

trait DataHandlerObjectTrait
{
    private $data;

    public function __set($name, $value)
    {
        // Prepare relations either simple or mm, 1-n etc.
        if (is_array($value)) {
            $ids = [];
            foreach ($value as $item) {
                if ($item instanceof DataHandlerObjectInterface) {
                    $ids[] = $item->getUid();
                }
            }

            if (! empty($ids)) {
                $value = implode(',', $ids);
            }
        } elseif ($value instanceof DataHandlerObjectInterface) {
            $value = $value->getUid();
        }

        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getUid()
    {
        if (! isset($this->data['uid'])) {
            $this->data['uid'] = StringUtility::getUniqueId('NEW');
        }

        return $this->data['uid'];
    }
}
