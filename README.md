# LARAVEL IMAGE
[![Latest Version](https://img.shields.io/github/release/ankitpokhrel/laravel-image.svg?style=flat-square)](https://github.com/ankitpokhrel/laravel-image/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/ankitpokhrel/laravel-image.svg?style=flat-square)](https://packagist.org/packages/ankitpokhrel/laravel-image)

Basic image upload and thumbnail management package for laravel 5.1. This package uses powerful libraries like
[Glide](http://glide.thephpleague.com/) for on-demand image manipulation.

> THIS PACKAGE IS NOT YET PRODUCTION READY

## Installation

> For 5.1 use [5.1](https://github.com/ankitpokhrel/laravel-image/tree/5.1) branch.

Pull the package via composer

```php
composer require "ankitpokhrel/laravel-image":"dev-master"
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
