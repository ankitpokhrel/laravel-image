<?php

namespace AnkitPokhrel\LaravelImage;

class ImageHelper
{
    /** @var array $attributes Default attributes */
    protected $attributes = [
        'alt'   => '',
        'class' => null,
    ];

    protected $options = [
        'fit' => 'crop-center',
    ];

    /**
     * @param $dir string Directory to search
     * @param string $image Image name
     * @param null $width
     * @param null $height
     * @param array $options
     *
     * @return string
     */
    public function image($dir, $image, $width = null, $height = null, array $options = [], array $attributes = [])
    {
        $attributes = array_replace_recursive($this->attributes, $attributes);
        $options    = array_replace_recursive($this->options, $options);

        $path = config('laravelimage.routePath') . '/' . $dir . $image . '?' . http_build_query($options, '', '&');

        if ( ! empty((int)$width)) {
            $path .= '&w=' . (int)$width;
        }

        if ( ! empty((int)$height)) {
            $path .= '&h=' . (int)$height;
        }

        return '<img src="' . asset($path) . '" ' . http_build_query($attributes, '', ' ') . ' />';
    }
}
