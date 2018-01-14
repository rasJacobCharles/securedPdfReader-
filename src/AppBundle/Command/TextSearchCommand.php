<?php
declare(strict_types = 1);

namespace AppBundle\Command;

use AppBundle\DataTransferObject\FileCollection;
use AppBundle\DataTransferObject\Result;
use AppBundle\DataTransferObject\SearchResult;
use AppBundle\Service\ScanFile;
use AppBundle\Service\ScanPath;
use AppBundle\ServiceCommand\ScanPathServiceCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class TextSearchCommand extends Command
{
    private const QUESTION_1 = 'menu';
    private const QUESTION_2 = 'search';
    private const MENU_OPTIONS = ['s'=>'search', 'q' => 'quit'];
    private const DEFAULT_MENU_OPTION = 0;
    private const MENU_QUESTION = 'Please select option';
    private const MENU_ERROR_MESSAGE = 'Option %s is invalid.';
    private const MENU_NEW_LINE = '============';
    private const MENU_MESSAGE = ['Please select a option', '1) Search, 2) Quit'];
    private const QUIT_OPTION = ['quit', 'q', '2'];
    private const COMMAND_ARGUMENT = 'path';
    private const DESCRIPTION = 'Search directory text files';
    private const COMMAND_HELP = 'This command search a directory for text files and then brings up a ranked search string with in';
    private const COMMAND_INPUT_HELP = 'The name of the folder';
    private const COMMAND_NAME = 'app:search';
    private const START_SCREEN = [
        'Scan folder',
        self::MENU_NEW_LINE
    ];

    /**
     * @var ScanPath
     */
    private $scanPath;

    /**
     * @var Helper
     */
    private $compandHelper;
    /**
     * @var array
     */
    private $commandQuestion = [];

    /**
     * @var boolean
     */
    private $exitLoop = false;
    /**
     * @var ScanFile
     */
    private $scanFile;

    public function __construct(ScanPath $scanPath, ScanFile $scanFile)
    {
        $this->scanPath = $scanPath;
        $this->scanFile = $scanFile;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->addArgument(self::COMMAND_ARGUMENT, InputArgument::REQUIRED, self::COMMAND_INPUT_HELP)
            ->setDescription(self::DESCRIPTION)->setHelp(self::COMMAND_HELP);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setMenuQuestions();
        $searchLocationPath = $input->getArgument(self::COMMAND_ARGUMENT);

        $output->writeln(self::START_SCREEN);

        while ($this->exitLoop == false) {
            $choice = $this->askMainMenuQuestion($input, $output);
            if (in_array($choice, self::QUIT_OPTION)) {
                $this->exitLoop = true;
            } else {
                $searchQuery  = $this->askSearchQuestion($input, $output);
                $fileCollection = $this->getFileCollection($searchLocationPath);
                $this->showSearchResult($output, $fileCollection, $searchLocationPath, $searchQuery);
            }
        }
    }

    private function setMenuQuestions(): void
    {
        $this->compandHelper                        = $this->getHelper('question');
        $this->commandQuestion[self::QUESTION_1]    = new ChoiceQuestion(self::MENU_QUESTION, self::MENU_OPTIONS, self::DEFAULT_MENU_OPTION, '');
        $this->commandQuestion[self::QUESTION_2]    = new Question('Please enter a search string?', '');
        $this->commandQuestion[self::QUESTION_1]->setErrorMessage(self::MENU_ERROR_MESSAGE);
    }

    private function askMainMenuQuestion(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(self::MENU_MESSAGE);
        $choice = $this->compandHelper->ask($input, $output, $this->commandQuestion[self::QUESTION_1]);

        return $choice;
    }

    private function askSearchQuestion(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('search <');
        $searchQuery = $this->compandHelper->ask($input, $output, $this->commandQuestion[self::QUESTION_2]);

        return $searchQuery;
    }

    private function getFileCollection (string $searchLocationPath): FileCollection
    {
        $scanPathCommand = ScanPathServiceCommand::create($searchLocationPath);

        return  $this->scanPath->scanPath($scanPathCommand);
    }

    private function showSearchResult(OutputInterface $output, FileCollection $fileCollection, string  $searchLocationPath, string $searchQuery): void
    {
        $output->writeln(sprintf('%s txt file/s in directory %s', $fileCollection->count(), $searchLocationPath));
        $output->writeln(sprintf('Search folder for "%s"', $searchQuery));
        $searchResult = $this->scanFile->scanCollection($searchQuery, $fileCollection, $searchLocationPath);
        $this->outputSearchResult($output, $searchResult);
    }

    private function outputSearchResult(OutputInterface $output, SearchResult $searchResult): void
    {
        foreach($searchResult->getTopTen() as $result) {

            /** @var Result $result*/
            $output->writeln(sprintf('%s: %s%%', $result->getFileName(),$result->getScore()));
        }
        if (empty($searchResult->getTopTen())) {
             $output->writeln([self::MENU_NEW_LINE, 'No result found', self::MENU_NEW_LINE]);
        }
    }
}