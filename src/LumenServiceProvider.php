<?php

namespace October\Rain\Config;

use Laravel\Lumen\Application;
use October\Rain\Config\ServiceProvider;

class LumenServiceProvider extends ServiceProvider
{
    /** @var  Application */
    protected $app;

    protected function getConfigPath(): string
    {
        return $this->app->getConfigurationPath();
    }
}
