<?php

namespace SKprods\AdvancedLaravel;

use SKprods\AdvancedLaravel\Exceptions\BadSourcePathException;
use SKprods\AdvancedLaravel\Exceptions\BadSourceFileException;

class Filesystem
{
    /**
     * Copy file to directory
     *
     * If the destination directory does not exist, it will automatically create it.
     *
     * @throws BadSourceFileException
     */
    public static function copyFile(string $source, string $destinationPath)
    {
        if (!file_exists($source)) {
            throw new BadSourceFileException($source);
        }

        $separated = explode('/', $source);
        $filename = end($separated);

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (!is_writable($destinationPath)) {
            chmod($destinationPath, 0755);
        }

        copy($source, "$destinationPath/$filename");
    }

    public static function copyDirectory(string $sourceDir, string $destinationDir)
    {
        if (!is_dir($sourceDir)) {
            throw new BadSourcePathException($sourceDir);
        }

        $directory = opendir($sourceDir);

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        while (($file = readdir($directory)) !== false) {
            if ($file != '.' && $file != '..') {
                if (is_dir($sourceDir . '/' . $file)) {
                    self::copyDirectory($sourceDir . '/' . $file, $destinationDir . '/'. $file);
                } else {
                    copy($sourceDir . '/' . $file, $destinationDir . '/' . $file);
                }
            }
        }
    }
}