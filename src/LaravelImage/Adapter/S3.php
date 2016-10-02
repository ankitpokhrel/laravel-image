<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

class S3 extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('s3');
    }
}
