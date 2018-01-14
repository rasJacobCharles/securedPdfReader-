<?php

namespace AppBundle\DataTransferObject;

use AppBundle\Exception\IncompatibleFileTypeException;
use PHPUnit\Framework\Assert;

abstract class File implements FileInterface
{
    /**
     * @var string
     */
    protected $fileExtension;

    /**
     * @var string
     */
    protected $filename;

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * {@inheritdoc}
     */
    public function setFileName(string $filename): void
    {
        $extend = pathinfo($filename, PATHINFO_EXTENSION );
        $this->throwExceptionOnIncorrectFileType($extend);

        $this->filename = $filename;
    }

    private function throwExceptionOnIncorrectFileType(string $extend)
    {
        if($extend !== $this->fileExtension) {
            throw new IncompatibleFileTypeException();
        }
    }
}