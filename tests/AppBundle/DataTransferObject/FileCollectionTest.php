<?php

namespace Tests\AppBundle\DataTransferObject;

use AppBundle\DataTransferObject\FileInterface;
use AppBundle\DataTransferObject\TextFile;
use AppBundle\DataTransferObject\FileCollection;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    public function test_is_able_create_file_collection()
    {
        $expectFile1 = new TextFile();
        $expectFile2 = new TextFile();
        $expectFile1->setFileName('testOne.txt');
        $expectFile2->setFileName('testOne.txt');
        $collection= new FileCollection();
        $collection->add($expectFile1);
        $collection->add($expectFile2);

        foreach ($collection->getFileCollection() as $file)
        {
            Assert::assertInstanceOf(FileInterface::class, $file);
        }
        Assert::assertTrue(is_array($collection->getFileCollection()));
        Assert::assertCount($collection->count(), $collection->getFileCollection());
    }
}