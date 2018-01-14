<?php

namespace Tests\AppBundle\Factory;

use AppBundle\DataTransferObject\TextFile;
use AppBundle\Factory\FileFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class FileFactoryTest extends TestCase
{

    public function test_successful_create_text_data_transfer_object_from_factory()
    {
        $expectedFileExtension = 'txt';
        $result =FileFactory::create($expectedFileExtension);

        Assert::assertInstanceOf(TextFile::class, $result);
    }

    /**
     * @expectedException \AppBundle\Exception\UnknownFileFactoryFileExtension
     */
    public function test_failed_create_text_data_transfer_object_with_invalid_file_type()
    {
        $unknownExtension= 'php';
        FileFactory::create($unknownExtension);
    }
}