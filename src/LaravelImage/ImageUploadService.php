<?php

namespace AnkitPokhrel\LaravelImage;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Handles all image upload operation
 *
 * @author Ankit Pokhrel
 */
class ImageUploadService
{
    /** @var string Image field */
    protected $field = 'image';

    /** @var string Upload dir */
    protected $uploadDir = 'upload_dir';

    /** @var string Original image name field */
    protected $originalImageNameField = 'original_image_name';

    /** @var string Upload base path */
    protected $basePath = 'uploads/';

    /** @var string Relative path to upload dir */
    protected $uploadPath = '';

    /** @var bool Is file uploaded in public dir? */
    protected $publicPath = true;

    /** @var string File save destination */
    protected $destination = '';

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
        $this->validationRules = $validationRules ? $validationRules : config('laravelimage.validationRules');

        // Set default upload folder
        $this->setUploadFolder('contents');
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
     * Set base path.
     *
     * @param $path
     * @param $publicPath
     */
    public function setBasePath($path, $publicPath = true)
    {
        $this->basePath   = $path;
        $this->publicPath = $publicPath;
    }

    /**
     * Get base path.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Enable or disable public path.
     *
     * @param $bool
     */
    public function setPublicPath($bool)
    {
        $this->publicPath = $bool;
    }

    /**
     * Get public path.
     */
    public function getPublicPath()
    {
        return $this->publicPath;
    }

    /**
     * @param $folder
     */
    public function setUploadFolder($folder)
    {
        $this->uploadPath = $this->basePath . $folder . '/' . $this->getUniqueFolderName() . '/';
        if ($this->publicPath) {
            $this->destination = public_path($this->uploadPath);
        } else {
            $this->destination = $this->uploadPath;
        }
    }

    /**
     * Get upload path
     *
     * @return string
     */
    public function getUploadPath()
    {
        return $this->uploadPath;
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
        $mimeType          = $file->getMimeType();

        $size = $file->getSize();
        if ($file->move($this->destination, $encryptedFileName)) {
            $this->uploadedFileInfo = [
                $this->originalImageNameField => $originalFileName,
                $this->field                  => $encryptedFileName,
                $this->uploadDir              => $this->getUploadPath(),
                'size'                        => $size,
                'extension'                   => $file->getClientOriginalExtension(),
                'mime_type'                   => $mimeType,
            ];

            return true;
        }

        return false;
    }

    /**
     * @return array|object
     */
    public function getValidationErrors()
    {
        return $this->errors;
    }

    /**
     * Clear out a folder and its content.
     *
     * @param string $folder Absolute path to the folder
     * @param bool $removeDirectory If you want to remove the folder as well
     *
     * @throws \Exception
     */
    public function clean($folder, $removeDirectory = false)
    {
        if ( ! is_dir($folder)) {
            throw new \Exception(('Not a folder.'));
        }

        array_map('unlink', glob($folder . DIRECTORY_SEPARATOR . '*'));
        if ($removeDirectory && file_exists($folder)) {
            rmdir($folder);
        }
    }

    /**
     * function to generate unique filename for images.
     *
     * @param string $filename
     *
     * @return string
     */
    public function getUniqueFilename($filename)
    {
        $uniqueName = uniqid();
        $fileExt    = explode('.', $filename);
        $mimeType   = end($fileExt);
        $filename   = $uniqueName . '.' . $mimeType;

        return $filename;
    }

    /**
     * Generate a random UUID for folder name (version 4).
     *
     * @see http://www.ietf.org/rfc/rfc4122.txt
     *
     * @return string RFC 4122 UUID
     *
     * @copyright Matt Farina MIT License https://github.com/lootils/uuid/blob/master/LICENSE
     */
    public function getUniqueFolderName()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 4095) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }
}
