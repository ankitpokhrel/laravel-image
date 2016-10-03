<?php

namespace AnkitPokhrel\LaravelImage\Adapter;

use AnkitPokhrel\LaravelImage\Util\UniquableTrait;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

abstract class AbstractAdapter implements AdapterInterface
{
    use UniquableTrait;

    /** @var string Image field */
    protected $field = 'image';

    /** @var string Upload dir */
    protected $uploadDir = 'upload_dir';

    /** @var string Original image name field */
    protected $originalImageNameField = 'original_image_name';

    /** @var string Relative path to upload dir */
    protected $basePath = null;

    /** @var bool Is file uploaded in absolute path? */
    protected $absolutePath = false;

    /** @var string Relative path to upload dir */
    protected $uploadFolder = '';

    /** @var array Uploaded file info */
    protected $uploadedFileInfo = [];

    /** @var string Image validation rules */
    protected $validationRules;

    /** @var array|object Validation errors */
    protected $errors = [];

    /**
     * @constructor
     *
     * @param null $validationRules
     */
    public function __construct($validationRules = null)
    {
        // Default validation rules
        $this->validationRules = $validationRules ? $validationRules : config('laravel-image.validation_rules');
    }

    /**
     * Get uploaded file info.
     *
     * @return array
     */
    public function getUploadedFileInfo()
    {
        return $this->uploadedFileInfo;
    }

    /**
     * Get uploaded file info.
     *
     * @param $fileInfo
     *
     * @return array
     */
    public function setUploadedFileInfo($fileInfo)
    {
        $this->uploadedFileInfo = $fileInfo;
    }

    /**
     * Set upload field.
     *
     * @param $fieldName
     */
    public function setUploadField($fieldName)
    {
        $this->field = $fieldName;
    }

    /**
     * get upload field.
     *
     * @return string
     */
    public function getUploadField()
    {
        return $this->field;
    }

    /**
     * Set upload directory.
     *
     * @param string $dir
     */
    public function setUploadDir($dir)
    {
        $this->uploadDir = $dir;
    }

    /**
     * get upload directory.
     *
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
     * Set original image name field.
     *
     * @param string $originalImageName
     */
    public function setOriginalImageNameField($originalImageName)
    {
        $this->originalImageNameField = $originalImageName;
    }

    /**
     * get original image name field.
     *
     * @return string
     */
    public function getOriginalImageNameField()
    {
        return $this->originalImageNameField;
    }

    /**
     * Set validation rules.
     *
     * @param $rules
     */
    public function setValidationRules($rules)
    {
        $this->validationRules = $rules;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /**
     * Get validation errors.
     *
     * @return array|object
     */
    public function getValidationErrors()
    {
        return $this->errors;
    }

    /**
     * Set base path.
     *
     * @param string $path
     * @param bool   $absolute
     */
    public function setBasePath($path, $absolute = false)
    {
        $this->basePath     = $path;
        $this->absolutePath = $absolute;
    }

    /**
     * Get absolute path.
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * Get absolute path.
     *
     * @param $bool
     */
    public function setAbsolutePath($bool)
    {
        $this->absolutePath = $bool;
    }

    /**
     * Set base path.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set upload folder.
     *
     * @param $folder
     */
    public function setUploadFolder($folder)
    {
        $this->uploadFolder = $this->getBasePath() . $folder . '/' . $this->getUniqueFolderName() . '/';
    }

    /**
     * Get upload path.
     *
     * @return string
     */
    public function getUploadFolder()
    {
        return $this->uploadFolder;
    }

    /**
     * Perform image validation.
     *
     * @param $file
     *
     * @return bool
     */
    protected function validate($file)
    {
        // Check if file is valid
        if ( ! $file->isValid()) {
            return false;
        }

        // Validate
        $validator = Validator::make([$this->field => $file], [$this->field => $this->validationRules]);
        if ($validator->fails()) {
            $this->errors = $validator;

            return false;
        }

        return true;
    }

    /**
     * Uploads file to required destination.
     *
     * @return bool
     */
    public function upload()
    {
        $file = Input::file($this->field);
        if ( ! $this->validate($file)) {
            return false;
        }

        $originalFileName  = $file->getClientOriginalName();
        $encryptedFileName = $this->getUniqueFilename($originalFileName);
        $uploadFolder      = $this->getUploadFolder();

        $uploadedFileInfo = [
            $this->originalImageNameField => $originalFileName,
            $this->field                  => $encryptedFileName,
            $this->uploadDir              => $this->getAbsolutePath() ? $uploadFolder : public_path($uploadFolder),
            'size'                        => $file->getSize(),
            'extension'                   => $file->getClientOriginalExtension(),
            'mime_type'                   => $file->getMimeType(),
        ];

        if ($this->write($this->getUploadFolder() . $encryptedFileName, $file)) {
            $this->setUploadedFileInfo($uploadedFileInfo);

            return true;
        }

        return false;
    }

    /**
     * Write the contents of a file.
     *
     * @param string $path
     * @param object $file
     * @param string $visibility
     *
     * @return bool
     */
    public function write($path, $file, $visibility = null)
    {
        return $this->getAdapter()->put($path, file_get_contents($file->getRealPath()), $visibility);
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
     * Delete the file at a given path.
     *
     * @param string|array $paths
     *
     * @return bool
     */
    public function remove($paths)
    {
        $this->getAdapter()->delete($paths);
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
        $this->getAdapter()->deleteDirectory($directory, $preserve);
    }
}
