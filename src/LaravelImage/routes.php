<?php

Route::get(config('laravel-image.route_path') . '/{path}',
    function (\League\Glide\Server $server, \Illuminate\Http\Request $request) {
        return $server->getImageResponse($request->getPathInfo(), $request->query());
    })->where('path', '.*');
