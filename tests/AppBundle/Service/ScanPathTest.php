<?php

namespace Tests\AppBundle\Service;

use AppBundle\DataTransferObject\FileCollection;
use AppBundle\DataTransferObject\FileInterface;
use AppBundle\DataTransferObject\TextFile;
use AppBundle\Service\ScanPath;
use AppBundle\ServiceCommand\ScanPathServiceCommand;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ScanPathTest extends TestCase
{
    public function test_is_scan_able_to_locate_text_files()
    {
        $path           = __DIR__;
        $fileType       = 'txt';
        $expectedCount  = 2;
        $scanPath       = new ScanPath($fileType);
        $expectResult   = new TextFile();
        $expectResult->setFileName('test.txt');
        $result         = $scanPath->scanPath(ScanPathServiceCommand::create($path));

        Assert::assertInstanceOf(FileCollection::class, $result);
        Assert::assertEquals($expectedCount, $result->count());
        Assert::assertInstanceOf(FileInterface::class, current($result->getFileCollection()));
        Assert::assertEquals($expectResult, current($result->getFileCollection()));
    }
}