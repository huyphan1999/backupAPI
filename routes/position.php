<?php
$api = app('Dingo\Api\Routing\Router');

// v1 version API
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => ['api.locale']], function ($api) {
        //Login
        $api->post('position/create', [
            'as' => 'position.create',
            'uses' => 'PositionController@createPosition',
        ]);
    });


});