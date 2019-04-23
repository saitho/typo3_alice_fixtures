<?php

namespace Ssch\Typo3AliceFixtures\Tests\Unit\Persister;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ssch\Typo3AliceFixtures\Domain\Model\File;
use Ssch\Typo3AliceFixtures\Persister\DataHandlerPersister;
use stdClass;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use UnexpectedValueException;

class DataHandlerPersisterTest extends UnitTestCase
{
    /**
     * @var DataHandlerPersister
     */
    protected $subject;

    /**
     * @var DataHandler|MockObject
     */
    private $dataHandler;

    protected function setUp()
    {
        $this->dataHandler = $this->getMockBuilder(DataHandler::class)->disableOriginalConstructor()->getMock();
        $this->subject = new DataHandlerPersister($this->dataHandler);
    }

    /**
     * @test
     */
    public function persistThrowsException(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->subject->persist(new stdClass());
    }

    /**
     * @test
     */
    public function persist(): void
    {
        $this->dataHandler->expects($this->once())->method('start');
        $this->dataHandler->expects($this->once())->method('process_datamap');

        $this->subject->persist(new File());
        $this->subject->flush();
    }
}
