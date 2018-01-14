<?php

namespace AppBundle\DataTransferObject;

class SearchResult
{
    private const ORDER_METHOD = "orderByScore";
    private const OFFSET = 0;
    private const LENGTH = 10;

    /**
     * @var Result[]
     */
    public $result = [];

    public function addResult($score, $fileName): void
    {
        $this->result[] = Result::create($score, $fileName);
    }

    public function getTopTen(): array
    {
        usort($this->result, [$this, self::ORDER_METHOD]);

        $topTen = array_reverse(array_slice($this->result, self::OFFSET, self::LENGTH, true));


        return $topTen;
    }

    protected function orderByScore(Result $resultA, Result $resultB): int
    {
        return strcmp($resultA->getScore(), $resultB->getScore());
    }
}