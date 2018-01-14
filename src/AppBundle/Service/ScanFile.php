<?php
declare(strict_types = 1);

namespace AppBundle\Service;

use AppBundle\DataTransferObject\FileCollection;
use AppBundle\DataTransferObject\SearchResult;

class ScanFile
{
    /**
     * @var string
     */
    private $searchQuery;

    /**
     * @var SearchResult
     */
    private $searchResult;

    /**
     * @var string
     */
    private $path;

    public function scanCollection(string $query, FileCollection $collection, string $path): SearchResult
    {
        $this->searchQuery = $query;
        $this->path        = $path;
        $this->searchResult = new SearchResult();

        foreach ($collection->getFileCollection() as $file){
            $this->searchFile($file->getFilename());
        }

        return $this->searchResult;
    }

    private function searchFile(string $fileName)
    {
        $fileContent = file_get_contents($this->path.DIRECTORY_SEPARATOR. $fileName);

        $queryScore = $this->getQueryStrength($fileContent);

        $this->searchResult->addResult($queryScore, $fileName);
    }


    private function getQueryStrength(string  $fileContent): float
    {
        $queryScore = ($this->isStringInContent($fileContent, $this->searchQuery))? 100 : $this->workDifferentQueryAndContent($fileContent);

        return round($queryScore);
    }

    private function workDifferentQueryAndContent(string $fileContent): float
    {
        $matchQuery = $this->createMatchQueryString($fileContent);

        $score = similar_text($matchQuery, $this->searchQuery);
        $length = strlen($this->searchQuery);

        return $score / $length * 100;
    }


    private function isStringInContent(string $fileContent, string $search): bool
    {
        $position = strpos($fileContent, $search);

        return ($position !==false)? true : false;
    }


    private function createMatchQueryString(string $fileContent): string
    {
        $searchQueryParts = explode(" ", $this->searchQuery);
        $wordsFoundInContent = [];
        foreach ($searchQueryParts as $searchQueryWord) {
            if ($this->isStringInContent($fileContent, $searchQueryWord)) {
                $wordsFoundInContent[] = $searchQueryWord;
            }
        }

        return  implode(" ", $wordsFoundInContent);
    }
}