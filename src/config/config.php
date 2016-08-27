<?php

return [

    /*
     |--------------------------------------------------------------------------
     | File upload directory
     |--------------------------------------------------------------------------
     |
     | Your upload directory
     */
    'uploadDir'          => public_path('uploads'),

    /*
     |--------------------------------------------------------------------------
     | Route prefix
     |--------------------------------------------------------------------------
     |
     | Your route prefix for glide
     */
    'routePath'          => 'laravel-image',

    /*
    |--------------------------------------------------------------------------
    | Default Validation Rules
    |--------------------------------------------------------------------------
    |
    | This option registers the default validation rules to apply while
    | uploading images.
    */
    'validationRules'    => 'mimes:jpeg,jpg,png|max:2048', //2mb

    /*
    |--------------------------------------------------------------------------
    | Image Fields
    |--------------------------------------------------------------------------
    |
    | Fields to treat as image fields. Required for validation.
    */
    'imageFields'        => [
        'image',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Validation error messages. Must be in format field.validationName.
    */
    'validationMessages' => [
        'image.max'   => 'Image may not be greater than 2 MB',
        'image.mimes' => 'Invalid image format. Please enter jpeg or png image.',
    ],
];
