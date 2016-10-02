<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

class S3 extends AbstractAdapter
{
    /**
     * Get adapter for respective service
     *
     * @return mixed
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('s3');
    }
}
