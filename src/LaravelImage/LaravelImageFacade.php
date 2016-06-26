<?php

namespace LaravelImage;

use Illuminate\Support\Facades\Facade;

class LaravelImageFacade extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelImage';
    }
}
