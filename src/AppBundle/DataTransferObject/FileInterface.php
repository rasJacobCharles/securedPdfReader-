<?php

namespace AppBundle\DataTransferObject;

use AppBundle\Exception\IncompatibleFileTypeException;

interface FileInterface
{
    public function getFilename(): string;

    /**
     * @throws IncompatibleFileTypeException
     */
    public function setFileName(string $filename): void;
}