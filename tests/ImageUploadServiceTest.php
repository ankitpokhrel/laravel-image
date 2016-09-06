<?php

namespace AnkitPokhrel\LaravelImage\Tests;

use AnkitPokhrel\LaravelImage\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \Mockery as m;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadServiceTest extends TestCase
{
    public static $functions;

    protected $uploadService;

    protected $validationRules = ['mimes:jpeg,jpg,png|max:2048'];

    public function setUp()
    {
        parent::setUp();

        $this->uploadService = m::mock(
            '\AnkitPokhrel\LaravelImage\ImageUploadService[_construct]',
            $this->validationRules
        );
    }

    /**
     * @test
     *
     * @covers ImageUploadService::setUploadField
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
     * @covers ImageUploadService::setUploadDir
     * @covers ImageUploadService::getUploadDir
     */
    public function set_upload_dir()
    {
        //test default value
        $this->assertEquals('upload_dir', $this->uploadService->getUploadDir());

        //test custom value
        $this->uploadService->setUploadDir('custom_upload_dir');
        $this->assertEquals('custom_upload_dir', $this->uploadService->getUploadDir());
    }

    /**
     * @test
     *
     * @covers ImageUploadService::setValidationRules
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
     * @covers ImageUploadService::setPublicPath
     * @covers ImageUploadService::getPublicPath
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
     * @covers ImageUploadService::setUploadFolder
     * @covers ImageUploadService::getUploadPath
     */
    public function set_upload_folder()
    {
        $regex = '/^uploads\/contents\/([a-z0-9]){8}-([a-z0-9]){4}-([a-z0-9]){4}-([a-z0-9]){4}-([a-z0-9]){12}\/$/i';
        $this->assertRegexp($regex, $this->uploadService->getUploadPath());

        $this->uploadService->setUploadFolder('users');

        $regex = '/^uploads\/users\/([a-z0-9]){8}-([a-z0-9]){4}-([a-z0-9]){4}-([a-z0-9]){4}-([a-z0-9]){12}\/$/i';
        $this->assertRegexp($regex, $this->uploadService->getUploadPath());
    }

    /**
     * @test
     *
     * @covers ImageUploadService::setOriginalImageNameField
     * @covers ImageUploadService::getOriginalImageNameField
     */
    public function set_original_image_name_field()
    {
        //test default value
        $this->assertEquals('original_image_name', $this->uploadService->getOriginalImageNameField());

        //test custom value
        $this->uploadService->setOriginalImageNameField('custom_original_image_name');
        $this->assertEquals('custom_original_image_name', $this->uploadService->getOriginalImageNameField());
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

    /**
     * @test
     *
     * @covers ImageUploadService::upload
     * @covers ImageUploadService::getUploadPath
     * @covers ImageUploadService::getUniqueFilename
     * @covers ImageUploadService::getUploadedFileInfo
     */
    public function image_is_uploaded_if_right_params_is_provided()
    {
        $file = new UploadedFile(
            $this->testImage,
            'ankit.png',
            'image/png',
            filesize($this->testImage),
            null,
            true
        );

        $imageName = '57cbcd31b0fde.png';
        $uploadDir = 'uploads/contents/639bd5bc-3dec-4bbf-af19-201931d1d0c2/';

        //mock input
        $input = m::mock(Request::class);
        $input->shouldReceive('setUserResolver')
              ->shouldReceive('file')
              ->andReturn($file);

        Input::swap($input);

        //mock upload service with required methods
        $uploadServiceMock = m::mock(
            '\AnkitPokhrel\LaravelImage\ImageUploadService[_construct,getUniqueFilename,getUploadPath]',
            $this->validationRules
        );

        $uploadServiceMock
            ->shouldReceive('getUniqueFilename')->withAnyArgs()->andReturn($imageName)
            ->shouldReceive('getUploadPath')->andReturn($uploadDir);

        $expected = [
            "original_image_name" => 'ankit.png',
            "image"               => $imageName,
            "upload_dir"          => $uploadDir,
            "size"                => filesize($this->testImage),
            "extension"           => 'png',
            "mime_type"           => 'image/png',
        ];

        $this->assertTrue($uploadServiceMock->upload());
        $this->assertEquals($expected, $uploadServiceMock->getUploadedFileInfo());
    }

    public function tearDown()
    {
        m::close();
    }
}
