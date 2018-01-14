<?php

namespace AppBundle\Factory;

use AppBundle\DataTransferObject\FileInterface;
use AppBundle\Exception\UnknownFileFactoryFileExtension;
use AppBundle\DataTransferObject;

class FileFactory
{
    private const EXPECTED_EXTENSION = ['txt' => DataTransferObject\TextFile::class];

    /**
     * @throws UnknownFileFactoryFileExtension
     */
    public static function create(string $fileExtension): FileInterface
    {
        self::throwExceptionIfExtensionUnknown($fileExtension);
        $className = self::EXPECTED_EXTENSION[$fileExtension];

        return new $className();
    }

    /**
     * @throws UnknownFileFactoryFileExtension
     */
    private static function throwExceptionIfExtensionUnknown(string $fileExtension)
    {
        if (!array_key_exists($fileExtension, self::EXPECTED_EXTENSION)) {
            throw new UnknownFileFactoryFileExtension();
        }
    }
}