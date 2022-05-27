<?php

namespace SKprods\AdvancedLaravel;

class Path
{
    /**
     * Преобразование пути к формату path/to/file
     */
    public static function prepareFile(string $path): string
    {
        return self::prepare($path, false);
    }

    /**
     * Преобразование пути к формату path/to/directory/
     */
    public static function prepareDirectory(string $path): string
    {
        return self::prepare($path, true);
    }

    private static function prepare(string $path, bool $isDir): string
    {
        if (str_starts_with($path, "/")) {
            $path = substr($path, 1);
        }

        if (!str_ends_with($path, "/") && $isDir) {
            $path .= '/';
        }

        return preg_replace("/\/+/", '/', $path);
    }
}
