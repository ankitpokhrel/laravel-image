<?php

namespace AnkitPokhrel\LaravelImage\Adapters;

class Local extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return app('laravel-image-filesystem')->disk('local');
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

    /**
     * {@inheritdoc}
     */
    public function getUploadFolder()
    {
        return $this->getAbsolutePath() ? $this->uploadFolder : public_path($this->uploadFolder);
    }
}
