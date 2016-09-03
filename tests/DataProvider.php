<?php

namespace AnkitPokhrel\LaravelImage\Tests;

class DataProvider
{
    /**
     * @return array
     */
    public function fileNames()
    {
        return [
            'png'     => ['image.png', 'png'],
            'jpg'     => ['image.jpg', 'jpg'],
            'jpeg'    => ['image.jpeg', 'jpeg'],
            'gif'     => ['image.gif', 'gif'],
            'default' => ['default_image.jpg', 'jpg'],
        ];
    }
}
