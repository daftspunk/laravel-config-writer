<?php

namespace Tekreme73\Laravel\ConfigWriter;

use Laravel\Lumen\Application;
use Tekreme73\Laravel\ConfigWriter\ServiceProvider;

class LumenServiceProvider extends ServiceProvider
{
    /** @var  Application */
    protected $app;

    protected function getConfigPath(): string
    {
        return $this->app->getConfigurationPath();
    }
}
