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

    /**
     * @return array
     */
    public function imageOptions()
    {
        $default        = '<img src="http://localhost/uploads/contents/639bd5bc-3dec-4bbf-af19-201931d1d0c2/image.png?fit=crop-center&w=300&h=200" alt="" class="" />';
        $withOptions    = '<img src="http://localhost/uploads/contents/639bd5bc-3dec-19d3-af19-201931d1d0c2/image.jpg?fit=crop-top-left&filt=sepia&w=800&h=600" alt="" class="" />';
        $withAttributes = '<img src="http://localhost/uploads/contents/639bd5bc-34de-4bbf-af19-201931d1d0c2/image.jpeg?fit=crop-top-right&filt=sepia&w=150&h=80" alt="Alt text of an image" class="custom-class" />';

        return [
            ['uploads/contents/639bd5bc-3dec-4bbf-af19-201931d1d0c2/', 'image.png', 300, 200, [], [], $default],
            [
                'uploads/contents/639bd5bc-3dec-19d3-af19-201931d1d0c2/', 'image.jpg',
                800, 600,
                ['fit' => 'crop-top-left', 'filt' => 'sepia'],
                [],
                $withOptions,
            ],
            [
                'uploads/contents/639bd5bc-34de-4bbf-af19-201931d1d0c2/', 'image.jpeg',
                150, 80,
                ['fit' => 'crop-top-right', 'filt' => 'sepia'],
                ['alt' => 'Alt text of an image', 'class' => 'custom-class'],
                $withAttributes,
            ],
        ];
    }
}
