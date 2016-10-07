<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk For Laravel Image
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the package. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "dropbox", "s3", "ftp", "rackspace"
    |
    */

    'driver' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

        'dropbox' => [
            'driver' => 'dropbox',
            'token'  => 'your-token',
            'secret' => 'your-secret',
        ],

        'ftp' => [
            'driver'   => 'ftp',
            'host'     => 'ftp.example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            /* optional config settings */
            'port'     => 21,
            'root'     => '/',
            'passive'  => true,
            'ssl'      => false,
            'timeout'  => 30,
        ],

    ],

    /*
     |--------------------------------------------------------------------------
     | Route prefix
     |--------------------------------------------------------------------------
     |
     | Your route prefix for glide
     */

    'route_path' => 'laravel-image',

    /*
    |--------------------------------------------------------------------------
    | Default Validation Rules
    |--------------------------------------------------------------------------
    |
    | This option registers the default validation rules to apply while
    | uploading images.
    |
    */

    'validation_rules' => 'mimes:jpeg,jpg,png|max:2048', //2mb

    /*
    |--------------------------------------------------------------------------
    | Image Fields
    |--------------------------------------------------------------------------
    |
    | Fields to treat as image fields. Required for validation.
    */

    'image_fields' => [
        'image',
        'avatar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Validation error messages. Must be in format field.validationName.
    |
    */

    'validation_messages' => [
        'image.max'   => 'Image may not be greater than 2 MB',
        'image.mimes' => 'Invalid image format. Please enter jpeg or png image.',
    ],
];
