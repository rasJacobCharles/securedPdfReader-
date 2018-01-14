<?php

namespace Tests\AppBundle\DataTransferObject;

use AppBundle\DataTransferObject\TextFile;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class TextFileTest extends TestCase
{

    public function test_successful_create_text_data_transfer_object()
    {
        $result = new TextFile();
        $expectedFilename = 'test.txt';
        $result->setFileName($expectedFilename);

        Assert::assertEquals($expectedFilename, $result->getFilename());

    }

    /**
     * @expectedException \AppBundle\Exception\IncompatibleFileTypeException
     */
    public function test_failed_create_text_data_transfer_object_with_invalid_file_type()
    {
        $result = new TextFile();
        $expectedFilename = 'test.bat';
        $result->setFileName($expectedFilename);
    }
}