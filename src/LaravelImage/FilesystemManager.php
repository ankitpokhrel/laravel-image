<?php

namespace AnkitPokhrel\LaravelImage;

use Dropbox\Client;
use Illuminate\Filesystem\FilesystemManager as LaravelFilesystemManager;
use League\Flysystem\Dropbox\DropboxAdapter;

class FilesystemManager extends LaravelFilesystemManager
{
    /**
     * {@inheritdoc}
     */
    protected function getConfig($name)
    {
        return $this->app['config']["laravel-image.disks.{$name}"];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['laravel-image.driver'];
    }

    /**
     * Create an instance of the dropbox driver.
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
