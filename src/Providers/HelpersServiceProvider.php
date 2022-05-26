<?php

namespace SKprods\AdvancedLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use SKprods\AdvancedLaravel\Handlers\ConsoleOutput;

class HelpersServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('console', function () {
            return new ConsoleOutput();
        });
    }
}