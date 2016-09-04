<?php

namespace AnkitPokhrel\LaravelImage\Tests;

use AnkitPokhrel\LaravelImage\ImageUploadService;
use \Mockery as m;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadServiceTest extends TestCase
{
    public static $functions;

    protected $uploadService;

    protected $testImage;

    public function setUp()
    {
        parent::setUp();

        $this->uploadService = m::mock('\AnkitPokhrel\LaravelImage\ImageUploadService[_construct]',
            ['mimes:jpeg,jpg,png|max:2048']);

        $this->testImage = __DIR__ . '/img/ankit.png';
    }

    /**
     * @test
     *
     * @covers ImageUploadService::getUploadField
     */
    public function set_upload_field()
    {
        //test default value
        $this->assertEquals('image', $this->uploadService->getUploadField());

        //test custom value
        $this->uploadService->setUploadField('custom_field');
        $this->assertEquals('custom_field', $this->uploadService->getUploadField());
    }

    /**
     * @test
     *
     * @covers ImageUploadService::getValidationRules
     */
    public function set_validation_rules()
    {
        //test default value
        $this->assertEquals($this->uploadService->getValidationRules(), 'mimes:jpeg,jpg,png|max:2048');

        //test custom value
        $this->uploadService->setValidationRules('mimes:png|max:1024');
        $this->assertEquals('mimes:png|max:1024', $this->uploadService->getValidationRules());
    }

    /**
     * @test
     *
     * @covers ImageUploadService::setBasePath
     * @covers ImageUploadService::getBasePath
     */
    public function set_base_path()
    {
        //test default value
        $this->assertEquals('uploads/', $this->uploadService->getBasePath());

        //test custom value
        $this->uploadService->setBasePath('custom_uploads/');
        $this->assertEquals('custom_uploads/', $this->uploadService->getBasePath());
    }

    /**
     * @test
     *
     * @covers ImageUploadService::getPublicPath
     * @covers ImageUploadService::setBasePath
     */
    public function set_public_path()
    {
        //test default value
        $this->assertTrue($this->uploadService->getPublicPath());

        //test custom value
        $this->uploadService->setBasePath('custom_uploads/', false);
        $this->assertFalse($this->uploadService->getPublicPath());
    }

    /**
     * @test
     *
     * @covers ImageUploadService::getUniqueFolderName()
     */
    public function get_unique_folder_name()
    {
        for ($i = 0; $i < 25; $i++) {
            $folders[] = $this->uploadService->getUniqueFolderName();
        }

        $this->assertEquals(25, count(array_unique($folders)));
    }

    /**
     * @test
     *
     * @covers       ImageUploadService::getUniqueFilename
     * @dataProvider AnkitPokhrel\LaravelImage\Tests\DataProvider::fileNames
     */
    public function get_unique_file_name_has_valid_extension($fileName, $ext)
    {
        $uniqueFileName = $this->uploadService->getUniqueFileName($fileName);

        $file    = explode('.', $fileName);
        $fileExt = end($file);

        $this->assertEquals($fileExt, $ext);
        $this->assertNotEquals($uniqueFileName, $fileName);
    }

    /**
     * @test
     *
     * @covers ImageUploadService::getUniqueFilename
     */
    public function get_unique_file_name_generates_unique_file_names()
    {
        for ($i = 0; $i < 25; $i++) {
            $files[] = $this->uploadService->getUniqueFileName(str_random(6));
        }

        $this->assertEquals(25, count(array_unique($files)));
    }

    /**
     * @test
     *
     * @covers ImageUploadService::validate
     */
    public function validate_file_with_right_params()
    {
        $file = new UploadedFile(
            $this->testImage,
            'ankit.png',
            'image/png',
            filesize($this->testImage),
            null,
            true
        );

        $this->assertTrue($this->uploadService->validate($file));
    }

    /**
     * @test
     *
     * @covers       ImageUploadService::validate
     * @dataProvider AnkitPokhrel\LaravelImage\Tests\DataProvider::invalidFileOptions
     */
    public function validate_fails_for_invalid_params($size, $type)
    {
        $file = new UploadedFile(
            $this->testImage,
            'ankit.png',
            $type,
            $size,
            null,
            false
        );

        $this->assertFalse($this->uploadService->validate($file));
    }

    public function tearDown()
    {
        m::close();
    }
}
