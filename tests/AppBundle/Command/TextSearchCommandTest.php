<?php

declare(strict_types=1);

namespace Tests\AppBundle\Command;

use AppBundle\Command\TextSearchCommand;
use AppBundle\Service\ScanFile;
use AppBundle\Service\ScanPath;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\Assert;


class TextSearchCommandTest extends KernelTestCase
{
    private const SEARCH_STRING = 'Now is the winter of our discontent';
    private const MENU_INPUT_1 = 's';
    private const MENU_INPUT_2 = 'q';

    /**
     * @test
     */
    public function test_is_command_able_run()
    {
        $expectedDirectory          = __DIR__;
        $expectedStringLine1        = 'Please select option';
        $expectedStringLine2        = sprintf('Search folder for "%s"', self::SEARCH_STRING);
        $expectedStringLine3        = "test1.txt: 26%";
        $expectedStringLine4        = "test2.txt: 100%";
        $expectedAmountOfTextFiles  = "2";
        $textSearchCommand          = $this->createCommandToTest($expectedDirectory);
        $output                     = $textSearchCommand->getDisplay();

        Assert::assertContains($expectedStringLine1,$output);
        Assert::assertContains($expectedDirectory,$output);
        Assert::assertContains($expectedAmountOfTextFiles,$output);
        Assert::assertContains($expectedStringLine2, $output);
        Assert::assertContains($expectedStringLine3, $output);
        Assert::assertContains($expectedStringLine4, $output);
    }


    private function createCommandToTest(string $directory): CommandTester
    {
        $application    = $this->createApplication();
        $commandTester  = $this->createCommandTest($directory, $application);

        return $commandTester;
    }


    private function createApplication(): Application
    {
        $kernel         = self::bootKernel();
        $application    = new Application($kernel);
        $scanPath       = new ScanPath('txt');
        $scanFile       = new ScanFile();
        $application->add(new TextSearchCommand($scanPath, $scanFile));

        return $application;
    }


    private function createCommandTest(string $directory, Application $application): CommandTester
    {
        $command        = $application->find('app:search');
        $commandTester  = new CommandTester($command);
        $commandTester->setInputs([self::MENU_INPUT_1, self::SEARCH_STRING, self::MENU_INPUT_2]);
        $commandTester->execute(['command' => $command->getName(), 'path' => $directory]);

        return $commandTester;
    }
}