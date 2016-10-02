<?php

namespace AnkitPokhrel\LaravelImage;

use Dropbox\Client;
use Illuminate\Filesystem\FilesystemManager as LaravelFilesystemManager;
use League\Flysystem\Dropbox\DropboxAdapter;

class FilesystemManager extends LaravelFilesystemManager
{
    /**
     * Get the filesystem connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["laravel-image.disks.{$name}"];
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['laravel-image.default'];
    }

    /**
     * Create an instance of the ftp driver.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function createDropboxDriver(array $config)
    {
        $client = new Client($config['token'], $config['secret']);

        return $this->adapt($this->createFlysystem(
            new DropboxAdapter($client), $config
        ));
    }
}
