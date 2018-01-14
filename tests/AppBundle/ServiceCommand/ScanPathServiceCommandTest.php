<?php

namespace Tests\AppBundle\HandlerCommand;

use AppBundle\ServiceCommand\ScanPathServiceCommand;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ScanPathServiceCommandTest extends TestCase
{
    private const FAKE_FOLDER = 'fakeFolder';

    public function test_command_factory()
    {
        $expectedPath = __DIR__;
        $result = ScanPathServiceCommand::create($expectedPath);

        Assert::assertInstanceOf(ScanPathServiceCommand::class, $result);
        Assert::assertEquals($expectedPath, $result->getPath());
    }

    /**
     * @expectedException \AppBundle\Exception\PathDoesNotExistException
     */
    public function test_command_failed_if_folder_does_not_exist()
    {
        $path = __DIR__.DIRECTORY_SEPARATOR. self::FAKE_FOLDER.DIRECTORY_SEPARATOR;
        ScanPathServiceCommand::create($path);
    }


}