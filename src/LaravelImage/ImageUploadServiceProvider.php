<?php

namespace LaravelImage;

use Illuminate\Support\ServiceProvider;

class ImageUploadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('laravelimage.php'),
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
    }

    /**
     * Register blade templates
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->directive('laravelimage', function ($options) {
            return "<?php echo \LaravelImage\LaravelImage::picture($options);?>";
        });
    }
}
