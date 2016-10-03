<?php

namespace AnkitPokhrel\LaravelImage;

use Illuminate\Filesystem\FilesystemServiceProvider as LaravelFilesystemServiceProvider;

class FilesystemServiceProvider extends LaravelFilesystemServiceProvider
{
    /**
     * Register the filesystem manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('laravel-image-filesystem', function () {
            return new FilesystemManager($this->app);
        });
    }

    /**
     * Get the default file driver.
     *
     * @return string
     */
    protected function getDefaultDriver()
    {
        return $this->app['config']['laravel-image.driver'];
    }

    /**
     * Get the default cloud based file driver.
     *
     * @return string
     */
    protected function getCloudDriver()
    {
        return $this->app['config']['laravel-image.cloud'];
    }
}
