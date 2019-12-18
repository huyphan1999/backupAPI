<?php

return  [

    'default' => 'mongodb',

    'connections' => [
        'mongodb' => [
            'driver' => 'mongodb',
            'dump_command_path' => '/opt/lampp/bin', // only the path, so without 'mysqldump' or 'pg_dump'
            'dump_command_timeout' => 60 * 5, // 5 minute timeout
            'dump_using_single_transaction' => true, // perform dump using a single transaction
            'host' => env('DB_HOST', 'p1.tanca.vn'),
            'port' => env('DB_PORT', '21171'),
            'database' => env('DB_DATABASE', 'api_group1'),
            'username' => env('DB_USERNAME', 'api_group1'),
            'password' => env('DB_PASSWORD', 'ApiGroup12019'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'name' => 'mongodb'
        ],
    ],
    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */
    'redis' => [
        'cluster' => env('REDIS_CLUSTER', false),
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'password' => env('REDIS_PASSWORD', null),
        ],
    ],

];
