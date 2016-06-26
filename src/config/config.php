<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Thumbnail Sizes
    |--------------------------------------------------------------------------
    |
    | This option registers the default thumbnail sizes that gets used while
    | creating image thumbs after upload.
    |
    | Options: 'w', 'h', 'crop'
    */
    'thumbSizes'         => [
        'large'  => [
            'w' => 800, //width of image
            'h' => 600, //height of image
        ],
        'medium' => [
            'w' => 500,
            'h' => 350,
        ],
        'thumb'  => [
            'w' => 150,
            'h' => 150,
        ],
    ],

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
    | Fields to treat as image fields. Require for validation.
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
