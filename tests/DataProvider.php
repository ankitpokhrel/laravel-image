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

    /**
     * @return array
     */
    public function invalidFileOptions()
    {
        return [
            //invalid size, valid mime
            [2049, 'image/png'],
            [3000, 'image/jpg'],
            [5000, 'image/jpeg'],
            [1000, 'image/gif'],

            //valid size, invalid mime
            [2000, 'image/tiff'],
            [2048, 'image/jpeeg'],
            [1000, 'application/json'],
            [30, 'application/pdf'],
            [800, 'text/plain'],
        ];
    }
}
