<?php

namespace AnkitPokhrel\LaravelImage\Adapters;

class Rackspace extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('rackspace');
    }
}
