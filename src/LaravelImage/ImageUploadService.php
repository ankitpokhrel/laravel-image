<?php

namespace AnkitPokhrel\LaravelImage;

use AnkitPokhrel\LaravelImage\Adapters\AdapterInterface;

/**
 * Handles all image upload operation.
 *
 * @author Ankit Pokhrel
 */
class ImageUploadService
{
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get current adapter.
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Uploads file to required destination.
     *
     * @return bool
     */
    public function upload()
    {
        return $this->getAdapter()->upload();
    }

    /**
     * Get the contents of a file.
     *
     * @param string $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return string
     */
    public function get($path)
    {
        return $this->getAdapter()->get($path);
    }

    /**
     * Recursively delete a directory.
     *
     * The directory itself may be optionally preserved.
     *
     * @param string $directory
     * @param bool   $preserve
     *
     * @return bool
     */
    public function clean($directory, $preserve = false)
    {
        $this->getAdapter()->clean($directory, $preserve);
    }

    /**
     * Set upload field.
     *
     * @param $fieldName
     *
     * @return $this
     */
    public function setUploadField($fieldName)
    {
        $this->getAdapter()->setUploadField($fieldName);

        return $this;
    }

    /**
     * Set upload folder.
     *
     * @param $path
     * @param $absolute
     *
     * @return $this
     */
    public function setBasePath($path, $absolute = false)
    {
        $this->getAdapter()->setBasePath($path, $absolute);

        return $this;
    }

    /**
     * Set upload folder.
     *
     * @param $folder
     *
     * @return $this
     */
    public function setUploadFolder($folder)
    {
        $this->getAdapter()->setUploadFolder($folder);

        return $this;
    }

    /**
     * Get validation errors.
     *
     * @return array|object
     */
    public function getValidationErrors()
    {
        return $this->getAdapter()->getValidationErrors();
    }

    /**
     * Get uploaded file info.
     *
     * @return array
     */
    public function getUploadedFileInfo()
    {
        return $this->getAdapter()->getUploadedFileInfo();
    }
}
