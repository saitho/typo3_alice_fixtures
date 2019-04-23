<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Persistence;

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

use Doctrine\DBAL\DBALException;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Fidry\AliceDataFixtures\Persistence\PurgerFactoryInterface;
use Fidry\AliceDataFixtures\Persistence\PurgerInterface;
use Ssch\Typo3AliceFixtures\Events;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException;

final class DatabasePurgerFactory implements PurgerInterface, PurgerFactoryInterface
{
    /**
     * @var ConnectionPool
     */
    private $connectionPool;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(ConnectionPool $connectionPool, Dispatcher $dispatcher)
    {
        $this->connectionPool = $connectionPool;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     */
    public function create(PurgeMode $mode, PurgerInterface $purger = null): PurgerInterface
    {
        return new static($this->connectionPool, $this->dispatcher);
    }

    /**
     * @inheritDoc
     * @throws DBALException
     * @throws InvalidSlotException
     * @throws InvalidSlotReturnException
     */
    public function purge(): void
    {
        $this->dispatcher->dispatch(__CLASS__, Events::BEFORE_PURGE_EVENT);

        // We are only purging the default connection, we do this intentionally
        $connection = $this->connectionPool->getConnectionByName('Default');
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0;');

        $schema = $connection->getSchemaManager()->createSchema();

        foreach ($schema->getTables() as $table) {
            $connection->truncate($table->getName());
        }

        $connection->exec('SET FOREIGN_KEY_CHECKS = 1;');

        $this->dispatcher->dispatch(__CLASS__, Events::AFTER_PURGE_EVENT);
    }
}
