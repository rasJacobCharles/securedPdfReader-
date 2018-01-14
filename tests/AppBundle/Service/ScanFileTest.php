<?php

namespace Tests\AppBundle\Service;

use AppBundle\DataTransferObject\FileCollection;
use AppBundle\DataTransferObject\Result;
use AppBundle\DataTransferObject\SearchResult;
use AppBundle\Factory\FileFactory;
use AppBundle\Service\ScanFile;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ScanFileTest extends TestCase
{
    public function test_is_scan_files_found_in_single_file()
    {
        $expectedCount = 100;
        $path = __DIR__;
        $collect = new FileCollection();
        $query = 'to be on not to be';
        $textFile = FileFactory::create('txt');
        $textFile->setFileName('test.txt');
        $collect->add($textFile);
        $scanFile = new ScanFile();
        $result = $scanFile->scanCollection($query, $collect, $path);

        Assert::assertInstanceOf(SearchResult::class, $result);
        Assert::assertInstanceOf(Result::class, current($result->getTopTen()));
        Assert::assertEquals($expectedCount, current($result->getTopTen())->getScore());

    }

    public function test_is_scan_files_found_in_file_collection()
    {
        $expectedCount = 100;
        $path = __DIR__;
        $collect = new FileCollection();
        $query = "Cry Havoc and let slip the dogs of war";
        $textFile = FileFactory::create('txt');
        $textFile->setFileName('test1.txt');
        $collect->add($textFile);
        $textFile = FileFactory::create('txt');
        $textFile->setFileName('test.txt');
        $collect->add($textFile);
        $scanFile = new ScanFile();
        $result = $scanFile->scanCollection($query, $collect, $path);

        Assert::assertInstanceOf(SearchResult::class, $result);
        Assert::assertInstanceOf(Result::class, current($result->getTopTen()));
        Assert::assertEquals($expectedCount, current($result->getTopTen())->getScore());
    }

    public function test_is_scan_files_found_in_single_file_that_not_100_percent_match()
    {
        $expectedCount = 61;
        $path = __DIR__;
        $collect = new FileCollection();
        $query = '"Cry Meow and let slip the cats of war';
        $textFile = FileFactory::create('txt');
        $textFile->setFileName('test1.txt');
        $collect->add($textFile);
        $scanFile = new ScanFile();
        $result = $scanFile->scanCollection($query, $collect, $path);

        Assert::assertInstanceOf(SearchResult::class, $result);
        Assert::assertInstanceOf(Result::class, current($result->getTopTen()));
        Assert::assertEquals($expectedCount, current($result->getTopTen())->getScore());

    }
}