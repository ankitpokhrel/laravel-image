<?php

namespace AnkitPokhrel\LaravelImage\Adapters;

interface AdapterInterface
{
    /**
     * Get adapter for respective service.
     *
     * @return mixed
     */
    public function getAdapter();
}
