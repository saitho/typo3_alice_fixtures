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
use Ssch\Typo3AliceFixtures\Domain\Model\FileReference;
use Ssch\Typo3AliceFixtures\Processors\FileProcessor;
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
     * @var FileReference[] File references have to be processed after flush happened as IDs need to be available
     */
    private $fileReferenceQueue = [];

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

        /**
         * Process file references the required all ids are created
         */
        if ($object instanceof FileReference) {
            $this->fileReferenceQueue[] = $object;
            return;
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

        // Process file references
        if (count($this->fileReferenceQueue)) {
            $newDataMap = [];
            /** @var DataHandlerObjectInterface $object */
            foreach ($this->fileReferenceQueue as $object) {
                $object = $this->replacePlaceholderIds($object, ['uid_foreign', 'uid_local']);
                $newDataMap[$object->getTableName()][$object->getUid()] = $object->toArray();
            }
            $this->dataHandler->start($newDataMap, []);
            $this->dataHandler->process_datamap();
            $this->fileReferenceQueue = [];
        }
    }

    protected function replacePlaceholderIds(DataHandlerObjectInterface $object, array $columnNames) {
        /**
         * Substitution array consists of the mapping done by FileProcessor and the mapping done by DataHandler
         */
        $substArray = array_merge(FileProcessor::getSubstitutionArray(), $this->dataHandler->substNEWwithIDs);

        $data = $object->toArray();
        foreach ($columnNames as $columnName) {
            if (!array_key_exists($columnName, $data)) {
                continue;
            }
            $columnValue = $data[$columnName];
            if (array_key_exists($columnValue, $substArray)) {
                $object->__set($columnName, $substArray[$columnValue]);
            }
        }
        return $object;
    }
}
