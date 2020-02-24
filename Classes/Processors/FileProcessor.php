<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Processors;

use Fidry\AliceDataFixtures\ProcessorInterface;
use Ssch\Typo3AliceFixtures\Domain\Model\DataHandlerObjectInterface;
use Ssch\Typo3AliceFixtures\Domain\Model\File;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\StorageRepository;

final class FileProcessor implements ProcessorInterface
{
    /**
     * @var array Maps generated IDs (NEW...) to the real ID from database
     */
    static protected $substitutionArray = [];

    public static function getSubstitutionArray(): array {
        return self::$substitutionArray;
    }

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
        /** @var $object DataHandlerObjectInterface */
        if ($object instanceof File) {
            $storage = $this->storageRepository->findByUid($object->getStorage());
            if ($storage) {
                $file = $storage->addFile($object->getIdentifier(), $storage->getDefaultFolder());

                $newUid = $file->getProperty('uid');
                self::$substitutionArray[(string)$object->getUid()] = $newUid;
                $object->reset();
                $object->__set('uid', $newUid);
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
