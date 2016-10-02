<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

class Dropbox extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('dropbox');
    }
}
