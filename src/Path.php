<?php

namespace SKprods\AdvancedLaravel;

class Path
{
    /**
     * Converting path to a format path/to/file
     */
    public static function prepareFile(string $path): string
    {
        if (str_starts_with($path, "/")) {
            $path = substr($path, 1);
        }

        return $path;
    }

    /**
     * Converting path to a format path/to/directory/
     */
    public static function prepareDirectory(string $path): string
    {
        if (str_starts_with($path, "/")) {
            $path = substr($path, 1);
        }

        if (!str_ends_with($path, "/")) {
            $path .= '/';
        }

        return $path;
    }
}