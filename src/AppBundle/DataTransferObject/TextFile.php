<?php

namespace AppBundle\DataTransferObject;

class TextFile extends File implements FileInterface
{
    private const FILETYPE = 'txt';

    protected $fileExtension = self::FILETYPE;
}