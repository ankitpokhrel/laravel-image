<?php

namespace LaravelImage;

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

    /** @var string Upload base path */
    protected $basePath = 'uploads/';

    /** @var bool Relative path to upload dir */
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
     * @param null $validationRules
     */
    public function __construct($validationRules = null)
    {
        /*
         * Default validation rules
         */
        $this->validationRules = $validationRules or config('laravelimage.validationRules');
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
     * Set base path.
     *
     * @param $path
     * @param $publicPath
     */
    public function setBasePath($path, $publicPath = true)
    {
        $this->basePath = $path;
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
        $rules = [$this->field => $this->validationRules];

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

        $originalFileName = $file->getClientOriginalName();
        $encryptedFileName = $this->getUniqueFilename($originalFileName);
        $mimeType = $file->getMimeType();

        if ($file->move($this->destination, $encryptedFileName)) {
            $this->uploadedFileInfo = [
                'original_image_name' => $originalFileName,
                $this->field          => $encryptedFileName,
                'upload_dir'          => $this->uploadPath,
                'size'                => $file->getSize(),
                'extension'           => $file->getClientOriginalExtension(),
                'mime_type'           => $mimeType,
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
     */
    public function clean($folder, $removeDirectory = false)
    {
        array_map('unlink', glob($folder . DIRECTORY_SEPARATOR . '*'));
        if ($removeDirectory && file_exists($folder) && is_dir($folder)) {
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
        $fileExt = explode('.', $filename);
        $mimeType = end($fileExt);
        $filename = $uniqueName . '.' . $mimeType;

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
            // 32 bits for "time_low"
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            // 16 bits for "time_mid"
            mt_rand(0, 65535),
            // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
            mt_rand(0, 4095) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }
}
