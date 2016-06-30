<?php

namespace LaravelImage\Tests;

use \Mockery as m;

class ImageUploadServiceTest extends \PHPUnit_Framework_TestCase
{
    public static $functions;

    protected $uploadService;

    public function setUp()
    {
        parent::setUp();

        $uploadService = m::mock('\LaravelImage\ImageUploadService[_construct]', ['mimes:jpeg,jpg,png|max:2048']);

        $this->uploadService = $uploadService;
    }

    /**
     * @covers ImageUploadService::getUploadField
     */
    public function testSetUploadField()
    {
        //test default value
        $this->assertEquals('image', $this->uploadService->getUploadField());

        //test custom value
        $this->uploadService->setUploadField('custom_field');
        $this->assertEquals('custom_field', $this->uploadService->getUploadField());
    }

    /**
     * @covers ImageUploadService::getValidationRules
     */
    public function testSetValidationRules()
    {
        //test default value
        $this->assertEquals($this->uploadService->getValidationRules(), 'mimes:jpeg,jpg,png|max:2048');

        //test custom value
        $this->uploadService->setValidationRules('mimes:png|max:1024');
        $this->assertEquals('mimes:png|max:1024', $this->uploadService->getValidationRules());
    }

    /**
     * @covers ImageUploadService::getBasePath
     */
    public function testSetBasePath()
    {
        //test default value
        $this->assertEquals('uploads/', $this->uploadService->getBasePath());

        //test custom value
        $this->uploadService->setBasePath('custom_uploads/');
        $this->assertEquals('custom_uploads/', $this->uploadService->getBasePath());
    }

    /**
     * @covers ImageUploadService::getPublicPath
     * @covers ImageUploadService::getBasePath
     */
    public function testSetPublicPath()
    {
        //test default value
        $this->assertTrue($this->uploadService->getPublicPath());

        //test custom value
        $this->uploadService->setBasePath('custom_uploads/', false);
        $this->assertFalse($this->uploadService->getPublicPath());
    }

    public function tearDown()
    {
        m::close();
    }
}
