<?php

namespace SKprods\AdvancedLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use SKprods\AdvancedLaravel\Handlers\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * @method static info(string $message)
 * @method static comment(string $message)
 * @method static error(string $message)
 * @method static message(string $message, string $foreground = null, string $background = null, array $options = [])
 * @method static ProgressBar bar(int $max = 0, float $minSecondsBetweenRedraws = 1 / 25)
 * @method static table(array $headers, $rows, $tableStyle = 'default', array $columnStyles = [])
 *
 * @see ConsoleOutput
 */
class Console extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'console';
    }
}