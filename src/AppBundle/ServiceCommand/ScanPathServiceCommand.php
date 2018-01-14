<?php
declare(strict_types=1);

namespace AppBundle\ServiceCommand;

use AppBundle\Exception\PathDoesNotExistException;

final class ScanPathServiceCommand
{
    /**
     * @var string
     */
    private $path;

    private function __construct(string $path)
    {
        $this->throwExceptionIfPathDoesNotExist($path);
        $this->path = $path;
    }

    public static function create(string $path): ScanPathServiceCommand
    {
        return new self($path);
    }


    private function throwExceptionIfPathDoesNotExist(string $path)
    {
        if (!file_exists ($path)){
            throw  new PathDoesNotExistException();
        }
    }

    public function getPath(): string
    {
        return strval($this->path);
    }
}