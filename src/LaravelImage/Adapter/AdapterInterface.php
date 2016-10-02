<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

interface AdapterInterface
{
    /**
     * Get adapter for respective service
     *
     * @return mixed
     */
    public function getAdapter();
}
