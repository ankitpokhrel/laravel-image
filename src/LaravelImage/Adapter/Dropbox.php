<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

class Dropbox extends AbstractAdapter
{
    /**
     * Get adapter for respective service
     *
     * @return mixed
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('dropbox');
    }
}
