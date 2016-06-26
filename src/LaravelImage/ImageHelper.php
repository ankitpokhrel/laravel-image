<?php

namespace LaravelImage;

class ImageHelper
{
    /** @var array $defaults Default attributes */
    protected $defaults = [
        'fit'     => 'crop-center',
        'alt'     => '',
        'class'   => null,
        'urlOnly' => false,
    ];
    
    /**
     * @param $dir Directory to search
     * @param $image Image name
     * @param null $width
     * @param null $height
     * @param array $options
     * @return string
     */
    public function image($dir, $image, $width = null, $height = null, array $options = [])
    {
        $options = array_merge($this->defaults, $options);

        $path = '/laravel-image/' . $dir . $image . '?fit=' . $options['fit'];

        if ( ! empty((int)$width)) {
            $path .= '&w=' . (int)$width;
        }

        if ( ! empty((int)$height)) {
            $path .= '&h=' . (int)$height;
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
        if (empty($options[0]) || empty($options[1])) {
            throw new \InvalidArgumentException('LaravelImage: dir and image attributes are required..');
        }

        return $this->image($options[0], $options[1], (isset($options[2]) ? $options[2] : null),
            (isset($options[3]) ? $options[3] : null), (isset($options[4]) ? $options[4] : null));
    }
}
