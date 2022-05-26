<?php

namespace SKprods\AdvancedLaravel\Exceptions;

use Exception;

class BadSourcePathException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("Source path [$path] doesn't exists");
    }
}