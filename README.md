# LARAVEL IMAGE
Basic image upload and thumbnail management package for laravel 5.1

## Installation

Pull the package via composer

```php
composer require "ankitpokhrel/laravel-image":"dev-master"
```

Include the service provider within `config/app.php`.

```php
LaravelImage\ImageUploadServiceProvider::class
```

And, for convenience, add a facade alias to this same file at the bottom:

```php
'ImageHelper' => LaravelImage\Image::class
```

Finally publish the configuration
```php
php artisan vendor:publish --provider="LaravelImage\ImageUploadServiceProvider"
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

    protected $file;

    /**
     * @param ImageUploadService $file
     */
    public function __construct(ImageUploadService $file)
    {
        ...

        //set properties for file upload
        $this->file = $file;
        $this->file->setUploadField('image'); //default is image
        $this->file->setUploadFolder('contents'); //default is public/uploads/contents
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

    if (Input::hasFile('image') && $this->file->upload()) {
        //file is uploaded, get uploaded file info
        $uploadedFileInfo = $this->file->getUploadedFileInfo();

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

    if (Input::hasFile('image') && $this->file->upload()) {
        ...

        //remove old files
        if ( ! empty($model->file_path)) {
            $this->file->clean(public_path($model->file_path), true);
        }
    }

    ...
}
```

#### Using facade to display images

Display from already registered thumbnails. It will try to generate new thumb if registered thumbnail doesn't exist.
```html
{!!
    \LaravelImage\LaravelImage::image($model->file_path, $model->image, [
        'alt' => $model->original_file_name,
        'size' => 'thumb'
    ])
!!}
```

Or, create image of custom size at runtime
```html
{!!
    \LaravelImage\LaravelImage::image($model->file_path, $model->image, [
        'alt' => $model->original_file_name,
        'size' => [
            'custom' => ['w' => 300, 'h' => 200, 'crop' => true]
        ]
    ])
!!}
```

## TODO
- Image cache implementation
- Complete documentation
