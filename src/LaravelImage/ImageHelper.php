<?php

namespace LaravelImage;

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
     * @param $dir Directory to search
     * @param $image Image name
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

    /**
     * Wrapper around image method.
     *
     * @param array $options
     *
     * @return string
     */
    public function picture(array $options)
    {
        if (empty($options[0]) || empty($options[1])) {
            throw new \InvalidArgumentException('LaravelImage: dir and image attributes are required..');
        }

        return $this->image($options[0], $options[1], (isset($options[2]) ? $options[2] : null),
            (isset($options[3]) ? $options[3] : null), (! empty($options[4]) ? $options[4] : []),
            (! empty($options[5]) ? $options[5] : []));
    }
}
