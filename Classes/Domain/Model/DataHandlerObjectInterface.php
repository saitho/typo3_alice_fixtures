<?php


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

interface DataHandlerObjectInterface
{
    public function toArray(): array;

    public function getTableName(): string;

    public function __get($name);

    public function __set($name, $value);

    public function __isset($name);

    public function reset(): void;

    /**
     * @return mixed
     */
    public function getUid();
}
