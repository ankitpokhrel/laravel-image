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
    protected $uploadFolder = 'uploads/';

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
     * return string
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
     * return string
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
     * return string
     */
    public function getOriginalImageNameField()
    {
        return $this->originalImageNameField;
    }

    /**
     * @param $rules
     */
    public function setValidationRules($rules)
    {
        $this->validationRules = $rules;
    }

    /**
     * Get validation rules.
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
     * Set upload folder.
     *
     * @param $folder
     */
    public function setUploadFolder($folder)
    {
        $this->uploadFolder  = $this->basePath . $folder . '/' . $this->getUniqueFolderName() . '/';
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

        $inputFile = [$this->field => $file];
        $rules     = [$this->field => $this->validationRules];

        // Validate
        $validator = Validator::make($inputFile, $rules);
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

        if ($this->getAdapter()->put(
            $this->getUploadFolder() . $encryptedFileName,
            file_get_contents($file->getRealPath()))
        ) {
            $this->setUploadedFileInfo([
                $this->originalImageNameField => $originalFileName,
                $this->field                  => $encryptedFileName,
                $this->uploadDir              => $this->getUploadFolder(),
                'size'                        => $file->getSize(),
                'extension'                   => $file->getClientOriginalExtension(),
                'mime_type'                   => $file->getMimeType(),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string|array  $paths
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
     * @param  string  $directory
     * @param  bool    $preserve
     * @return bool
     */
    public function clean($directory, $preserve = false)
    {
        $this->getAdapter()->deleteDirectory($directory, $preserve);
    }
}
