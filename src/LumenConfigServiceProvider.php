<?php

namespace Tekreme73\Laravel\ConfigWriter;

use Illuminate\Filesystem\Filesystem;
use Tekreme73\Laravel\ConfigWriter\AbstractConfigServiceProvider;

class LumenConfigServiceProvider extends AbstractConfigServiceProvider
{
    protected function getFilesFrom($app): Filesystem
    {
        return $app['files'];
    }

    protected function getConfigPathFrom($app): string
    {
        return $app->getConfigurationPath();
    }
}