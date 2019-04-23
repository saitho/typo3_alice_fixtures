<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Persister;

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

use Fidry\AliceDataFixtures\Persistence\PersisterInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ssch\Typo3AliceFixtures\Domain\Model\DataHandlerObjectInterface;
use Ssch\Typo3AliceFixtures\Domain\Model\File;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use UnexpectedValueException;

final class DataHandlerPersister implements PersisterInterface
{

    /**
     * @var DataHandler
     */
    private $dataHandler;

    /**
     * @var array
     */
    private $dataMap = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(DataHandler $dataHandler, LoggerInterface $logger = null)
    {
        $this->dataHandler = $dataHandler;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function persist($object): void
    {
        if (! $object instanceof DataHandlerObjectInterface) {
            throw new UnexpectedValueException('Must be of type DataHandlerObjectInterface');
        }

        $this->dataMap[$object->getTableName()][$object->getUid()] = $object->toArray();
    }

    /**
     * @inheritDoc
     */
    public function flush(): void
    {
        if (! empty($this->dataMap)) {
            $this->dataHandler->start($this->dataMap, []);
            $this->dataHandler->process_datamap();

            if (!empty($this->dataHandler->errorLog)) {
                foreach ($this->dataHandler->errorLog as $error) {
                    $this->logger->error($error);
                }
            }

            $this->dataMap = [];
        }
    }
}
