<?php

namespace AppBundle\DataTransferObject;


class FileCollection
{
    /**
     * @var FileInterface[]
     */
    private $fileCollection = [];

    /**
     * @return FileInterface[]
     */
    public function getFileCollection(): array
    {
        return $this->fileCollection;
    }

    public function count(): int
    {
        return count($this->fileCollection);
    }

    public function add(FileInterface $file)
    {
        $this->fileCollection[] = $file;
    }
}