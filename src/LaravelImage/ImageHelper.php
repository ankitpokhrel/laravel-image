<?php

namespace LaravelImage;

use Intervention\Image\Facades\Image;

class ImageHelper
{
    /** @var array $defaults Default attributes */
    protected $defaults = [
        'alt'     => '',
        'size'    => null,
        'urlOnly' => false,
    ];

    protected $thumbnailSizes;

    public function __construct()
    {
        /*
         * Default thumbnail sizes
         */
        $this->thumbnailSizes = config('laravelimage.thumbSizes');
    }

    /**
     * @param $dir Directory to search
     * @param $image Image name
     * @param array $options
     *
     * @return string
     */
    public function image($dir, $image, array $options = [])
    {
        $options = array_merge($this->defaults, $options);

        $size = $options['size'];
        $path = $dir . $image;
        if ( ! empty($size)) {
            if (is_array($size)) {
                $key = key($size);
                $this->resizeImage($dir, $image, $size);
            } else {
                $key = $size;
                $originalFile = public_path($dir) . $image;
                $file = public_path($dir) . $key . '_' . $image;
                if ( ! file_exists($file) && file_exists($originalFile) && isset($this->thumbnailSizes[$key])) {
                    $this->resizeImage($dir, $image, [
                        $key => $this->thumbnailSizes[$key],
                    ]);
                }
            }

            $path = $dir . $key . '_' . $image;
        }

        if ($options['urlOnly']) {
            return asset($path);
        }

        $class = '';
        if ( ! empty($options['class'])) {
            $class = 'class="' . $options['class'] . '"';
        }

        return '<img src="' . asset($path) . '" alt="' . $options['alt'] . '" ' . $class . ' />';
    }

    /**
     * Wrapper around image method.
     *
     * @param array $options
     * @return string
     */
    public function picture(array $options)
    {
        if (empty($options['dir']) || empty($options['image'])) {
            throw new \InvalidArgumentException('LaravelImage: dir and image attributes are required..');
        }

        return $this->image($options['dir'], $options['image'], $options['attributes']);
    }

    /**
     * Resize image to specified size.
     *
     * @param $dir Image directory
     * @param $image Actual image
     * @param array $options Resize options
     *
     * @return mixed
     */
    private function resizeImage($dir, $image, array $options)
    {
        $key = key($options);
        $width = $options[$key]['w'];
        $height = $options[$key]['h'];
        $crop = isset($options[$key]['crop']) ? (bool)$options[$key]['crop'] : false;

        if ($crop) {
            $img = Image::make(public_path($dir) . $image)->resize($width, $height);
        } else {
            $img = Image::make(public_path($dir) . $image)->resize($width, null,
                function ($constraint) {
                    $constraint->aspectRatio();
                });
        }

        return $img->save(public_path($dir) . $key . '_' . $image);
    }
}
