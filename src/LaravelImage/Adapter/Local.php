<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

class Local extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('public');
    }

    /**
     * {@inheritdoc}
     */
    public function write($path, $file, $visibility = null)
    {
        if ($this->getAbsolutePath()) {
            return $file->move($this->getUploadFolder(), $this->getUniqueFilename($file->getClientOriginalName()));
        }

        return parent::write($path, $file, $visibility);
    }
}
