<?php
declare(strict_types = 1);

namespace AppBundle\DataTransferObject;

class Result
{
    /**
     * @var float
     */
    private $score = 0;

    /**
     * @var string
     */
    private $fileName;

    private function __construct(float $score, string $fileName)
    {
        $this->score     = $score;
        $this->fileName  = $fileName;
    }

    public static function create(float $score, string $fileName): self
    {
        return new self($score, $fileName);
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }


}