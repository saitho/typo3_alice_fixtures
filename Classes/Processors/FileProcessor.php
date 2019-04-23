<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Processors;

use Fidry\AliceDataFixtures\ProcessorInterface;
use Ssch\Typo3AliceFixtures\Domain\Model\File;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\StorageRepository;

final class FileProcessor implements ProcessorInterface
{

    /**
     * @var StorageRepository
     */
    private $storageRepository;

    /**
     * FileProcessor constructor.
     *
     * @param StorageRepository $storageRepository
     */
    public function __construct(StorageRepository $storageRepository)
    {
        $this->storageRepository = $storageRepository;
    }

    /**
     * @inheritDoc
     * @throws ExistingTargetFileNameException
     */
    public function preProcess(string $id, $object): void
    {
        if ($object instanceof File) {
            $storage = $this->storageRepository->findByUid($object->getStorage());
            if ($storage) {
                $file = $storage->addFile($object->getIdentifier(), $storage->getDefaultFolder());
                $object->reset();
                $object->uid = $file->getProperty('uid');
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function postProcess(string $id, $object): void
    {
    }
}
