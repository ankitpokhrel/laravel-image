<?php

namespace LaravelImage;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

class ImageUploadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ( ! $this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('laravel-image.php'),
        ]);

        $this->registerBladeExtensions();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('LaravelImage\ImageUploadService');

        $this->app->singleton('laravelImage', function () {
            return $this->app->make('LaravelImage\ImageHelper');
        });

        $this->registerGlide();
    }

    /**
     * Register glide
     */
    protected function registerGlide()
    {
        $this->app->singleton('\League\Glide\Server', function($app) {

            $fileSystem = $app->make(Filesystem::class);

            $uploadDir = config('laravelimage.uploadDir');
            // Set source filesystem
            $source = new LeagueFilesystem(
                new Local($uploadDir)
            );

            // Set cache filesystem
            $cache = new LeagueFilesystem(
                new Local($fileSystem->getDriver()->getAdapter()->getPathPrefix() . '/laravel-image-cache')
            );

            // Setup glide server
            return ServerFactory::create([
                'source' => $source,
                'cache' => $cache,
                'base_url' => 'laravel-image/' . basename($uploadDir),
                'response' => new LaravelResponseFactory()
            ]);
        });
    }

    /**
     * Register blade templates
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->directive('laravelImage', function ($options) {
            return "<?php echo \LaravelImage\LaravelImageFacade::picture(array $options);?>";
        });
    }
}
