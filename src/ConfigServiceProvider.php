<?php

namespace Tekreme73\Laravel\ConfigWriter;

use Illuminate\Support\ServiceProvider;
use Tekreme73\Laravel\ConfigWriter\FileWriter;
use Tekreme73\Laravel\ConfigWriter\Repository;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind it only once so we can reuse in IoC
        $this->app->singleton($this->repository(), function($app, $items) {
            $writer = new FileWriter($this->getFilesFrom($app), $this->getConfigPathFrom($app));
            return new Repository($writer, $items);
        });

        $this->app->extend('config', function($config, $app) {
            // Capture the loaded configuration items
            $config_items = $config->all();
            return $app->make($this->repository(), $config_items);
        });
    }

    public function repository(): string
    {
        return 'Tekreme73\Laravel\ConfigWriter\Repository';
    }

    protected function getFilesFrom($app): Filesystem
    {
        return $app['files'];
    }

    protected function getConfigPathFrom($app): string
    {
        return $app['path.config'];
    }
}
