<?php

namespace AnkitPokhrel\LaravelImage;

class ImageHelper
{
    /** @var array $attributes Default attributes */
    protected $attributes = [
        'alt'   => null,
        'class' => null,
    ];

    /** @var array Default options */
    protected $options = [
        'fit' => 'crop-center',
    ];

    /**
     * Display image using glide route.
     *
     * @param $dir string Directory to search
     * @param string $image   Image name
     * @param null   $width
     * @param null   $height
     * @param array  $options
     *
     * @return string
     */
    public function image($dir, $image, $width = null, $height = null, array $options = [], array $attributes = [])
    {
        $attributes = array_replace_recursive($this->attributes, $attributes);
        $options    = array_replace_recursive($this->options, $options);

        $path = config('laravel-image.route_path') . '/' . $dir . $image . '?' . http_build_query($options, '', '&');

        if ( ! empty((int) $width)) {
            $path .= '&w=' . (int) $width;
        }

        if ( ! empty((int) $height)) {
            $path .= '&h=' . (int) $height;
        }

        return '<img src="' . asset($path) . '" ' . $this->buildAttributes($attributes) . ' />';
    }

    /**
     * @param $attributes
     *
     * @codeCoverageIgnore
     *
     * @return null|string
     */
    protected function buildAttributes($attributes)
    {
        if ( ! $attributes) {
            return;
        }

        $attributeMap = [];
        foreach ($attributes as $attribute => $value) {
            $attributeMap[] = $attribute . '="' . $value . '"';
        }

        return implode($attributeMap, ' ');
    }
}
