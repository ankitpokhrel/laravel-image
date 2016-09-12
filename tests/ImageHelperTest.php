<?php

namespace AnkitPokhrel\LaravelImage\Tests;

use AnkitPokhrel\LaravelImage\ImageHelper;

/**
 * @coversDefaultClass AnkitPokhrel\LaravelImage\ImageHelper
 */
class ImageHelperTest extends TestCase
{
    protected $helper;

    public function setUp()
    {
        parent::setUp();

        $this->helper = new ImageHelper();
    }

    /**
     * @test
     *
     * @covers       ::image
     * @dataProvider AnkitPokhrel\LaravelImage\Tests\DataProvider::imageOptions
     */
    public function it_generates_valid_image_tag($uploadDir, $image, $width, $height, $options, $attributes, $output)
    {
        $this->assertEquals($output, $this->helper->image($uploadDir, $image, $width, $height, $options, $attributes));
    }
}
