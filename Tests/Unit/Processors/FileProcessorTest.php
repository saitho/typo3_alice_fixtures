<?php

namespace Ssch\Typo3AliceFixtures\Tests\Unit\Processors;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ssch\Typo3AliceFixtures\Domain\Model\File;
use Ssch\Typo3AliceFixtures\Processors\FileProcessor;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\StringUtility;

class FileProcessorTest extends UnitTestCase
{
    /**
     * @var FileProcessor
     */
    protected $subject;

    /**
     * @var MockObject|StorageRepository
     */
    private $storageRepository;

    /**
     * @var MockObject|ResourceStorage
     */
    private $storage;

    /**
     * @var Folder|MockObject
     */
    private $defaultFolder;

    protected function setUp()
    {
        $this->defaultFolder = $this->getMockBuilder(Folder::class)->disableOriginalConstructor()->getMock();

        $this->storage = $this->getMockBuilder(ResourceStorage::class)->disableOriginalConstructor()->getMock();
        $this->storage->method('getDefaultFolder')->willReturn($this->defaultFolder);

        $this->storageRepository = $this->getMockBuilder(StorageRepository::class)->getMock();
        $this->storageRepository->method('findByUid')->willReturn($this->storage);
        $this->subject = new FileProcessor($this->storageRepository);
    }

    /**
     * @test
     */
    public function doNothingNoFileObjectGiven(): void
    {
        $this->storageRepository->expects($this->never())->method('findByUid');
        $this->subject->preProcess(StringUtility::getUniqueId('NEW'), new \stdClass());
    }

    /**
     * @test
     */
    public function addFileToDefaultStorageSuccessfully(): void
    {
        $object = new File();

        $this->storageRepository->expects($this->once())->method('findByUid');
        $this->storage->expects($this->once())->method('getDefaultFolder');
        $addedFile = $this->getMockBuilder(FileInterface::class)->getMock();
        $addedFile->expects($this->once())->method('getProperty')->with('uid')->willReturn(1);
        $this->storage->expects($this->once())->method('addFile')->willReturn($addedFile);
        $this->subject->preProcess(StringUtility::getUniqueId('NEW'), $object);

        $this->assertEquals($object->toArray(), ['uid' => 1]);
    }
}
