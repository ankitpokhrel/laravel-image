# LARAVEL IMAGE
[![Latest Version](https://img.shields.io/github/release/ankitpokhrel/laravel-image.svg?style=flat-square)](https://github.com/ankitpokhrel/laravel-image/releases)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/ankitpokhrel/laravel-image/5.2.svg?style=flat-square)](https://scrutinizer-ci.com/g/ankitpokhrel/laravel-image/?branch=5.2)
[![Travis Build](https://img.shields.io/travis/ankitpokhrel/laravel-imag/5.2.svg?style=flat-square)](https://travis-ci.org/ankitpokhrel/laravel-image)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ankitpokhrel/laravel-image/5.2.svg?style=flat-square)](https://scrutinizer-ci.com/g/ankitpokhrel/laravel-image/?branch=5.2)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/ankitpokhrel/laravel-image.svg?style=flat-square)](https://packagist.org/packages/ankitpokhrel/laravel-image)

Basic image upload and thumbnail management package for laravel 5+. This package uses [Glide](http://glide.thephpleague.com/) library from the php league for on-demand image manipulation.

## Installation

> For 5.1 use [5.1](https://github.com/ankitpokhrel/laravel-image/tree/5.1) branch.  
> For 5.2 use [5.2](https://github.com/ankitpokhrel/laravel-image/tree/5.2) branch.  
> For 5.3 use [master](https://github.com/ankitpokhrel/laravel-image) branch.

Pull the package via composer

```php
composer require "ankitpokhrel/laravel-image":"5.2.x-dev"
```

Include the service provider within `config/app.php`.

```php
AnkitPokhrel\LaravelImage\ImageUploadServiceProvider::class
```

Finally publish the configuration
```php
php artisan vendor:publish --provider="AnkitPokhrel\LaravelImage\ImageUploadServiceProvider"
```

## Configuration
You can add default thumbnail configuration to `config/laravelimage.php`.

## Usage

#### Uploading Image
Within your controllers, get access to image upload service via dependency injection
```php
class ContentsController extends Controller
{
    ...

    protected $image;

    /**
     * @param ImageUploadService $image
     */
    public function __construct(ImageUploadService $image)
    {
        ...

        //set properties for file upload
        $this->image = $image;
        $this->image->setUploadField('image'); //default is image
        $this->image->setUploadFolder('contents'); //default is public/uploads/contents
    }

    ...

```

And then, in your store or update method you can perform image upload
```php
/**
 * Store method
 */
public function store(ContentRequest $request)
{
    $input = $request->all();

    if (Input::hasFile('image') && $this->image->upload()) {
        //image is uploaded, get uploaded image info
        $uploadedFileInfo = $this->image->getUploadedFileInfo();

        ...
        //do whatever you like with the information
        $input = array_merge($input, $uploadedFileInfo);
    } else {
        //get validator object
        $validator = $this->image->getValidationErrors();
        
        //get error messages
        $errors = $validator->messages()->all();
    }

    ...
}

/**
 * Update method
 */
public function update(Request $request, $id)
{
    ...

    if (Input::hasFile('image') && $this->image->upload()) {
        ...

        //remove old files
        if ( ! empty($model->file_path)) {
            $this->image->clean(public_path($model->file_path), true);
        }
    }

    ...
}
```

#### Customizing upload path

Sometime you may want to group uploaded image or even store image somewhere else other than the public folder. 
You can do it by setting base path. For example, this settings below will store images inside 
`public/uploads/user-images/users/` directory.

```php
//set base path
$this->file->setBasePath('uploads/user-images/');

//set upload folder
$this->file->setUploadFolder('users');
```

If you want to upload image in other places other than `public` folder, you can provide second parameter as `false` 
to the base path method.

```php
//set absolute base path
$this->file->setBasePath('/absolute/path/to/your/folder/uploads/', true);

//set upload folder
$this->file->setUploadFolder('users');
```

This will upload image to `/absolute/path/to/your/folder/uploads/users` folder. Make sure that the folder has proper 
permission to store the images.

#### Using blade directive to display images

Display full image
```html
@laravelImage($uploadDir, $imageName)
```

Create image of custom size at runtime
```html
<-- @laravelImage(uploadDir, imageName, width, height) -->
@laravelImage($uploadDir, $imageName, 300, 200)
```

Options & attributes
```html
<-- @laravelImage(uploadDir, imageName, width, height, options, attributes) -->
@laravelImage($uploadDir, $imageName, 300, 200, [
    'fit' => 'crop-top-left',
    'filt' => 'sepia'
], [
    'alt' => 'Alt text of an image',
    'class' => 'custom-class'
])
```

> Options can be any glide options. See [thephpleague/glide](http://glide.thephpleague.com/) for more info on options.

#### Displaying image without blade
 
 Image source should be in the format `laravelimage.routePath/uploadDir/image?options`, where `laravelimage.routePath` is from configuration file.
 So if you set your `routePath` to `cache`, the image url will be something like this.

```html
<img src="/cache/{{ $uploadDir . $image  }}?w=128&fit=crop-center" alt="" />
```
