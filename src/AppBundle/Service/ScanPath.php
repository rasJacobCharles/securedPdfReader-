<?php

namespace AppBundle\Service;

use AppBundle\DataTransferObject\FileCollection;
use AppBundle\Factory\FileFactory;
use AppBundle\ServiceCommand\ScanPathServiceCommand;

class ScanPath
{
    /**
     * @var string
     */
    private $fileExtension;

    /**
     * @var FileCollection
     */
    private $fileCollection;

    /**
     * @var array
     */
    private $scanResult;

    public function __construct(string $fileType)
    {
        $this->fileExtension = $fileType;
    }

    public function scanPath(ScanPathServiceCommand$command): FileCollection
    {
        $this->fileCollection = new FileCollection();
        $this->scanResult = [];
        $this->findAllFileOfSetType($command->getPath());
        $this->assignResultToFileCollection();

        return $this->fileCollection;
    }

    private function findAllFileOfSetType(string $path)
    {
       foreach (scandir($path) as $result) {

           $this->addFileToScanResultOfCorrespondingType($result);
       }
    }

    private function addFileToScanResultOfCorrespondingType(string $filename)
    {
        if (pathinfo($filename, PATHINFO_EXTENSION) === $this->fileExtension) {
            $this->scanResult[] = $filename;
        }
    }

    private function assignResultToFileCollection()
    {
        foreach ($this->scanResult as $filename)
        {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $file= FileFactory::create($extension);
            $file->setFileName($filename);
            $this->fileCollection->add($file);
        }
    }
}