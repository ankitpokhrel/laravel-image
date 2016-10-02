<?php

namespace AnkitPokhrel\LaravelImage;

use AnkitPokhrel\LaravelImage\Adapter\AdapterInterface;

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
     * Recursively delete a directory.
     *
     * The directory itself may be optionally preserved.
     *
     * @param  string  $directory
     * @param  bool    $preserve
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
     */
    public function setUploadField($fieldName)
    {
        $this->getAdapter()->setUploadField($fieldName);
    }

    /**
     * Set upload folder.
     *
     * @param $path
     * @param $absolute
     */
    public function setBasePath($path, $absolute = false)
    {
        $this->getAdapter()->setBasePath($path, $absolute);
    }

    /**
     * Set upload folder.
     *
     * @param $folder
     */
    public function setUploadFolder($folder)
    {
        $this->getAdapter()->setUploadFolder($folder);
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
