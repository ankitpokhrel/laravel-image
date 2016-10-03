<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

class Ftp extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('ftp');
    }
}
