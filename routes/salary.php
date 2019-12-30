<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
// $app->get('/', function () use ($app) {
//     return $app->version();
// });

$api = app('Dingo\Api\Routing\Router');

// v1 version API
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => ['api.locale']], function ($api) {
        //Login        
<<<<<<< HEAD
        $api->get('salary/register', [
            'as' => 'salary.register',
            'uses' => 'SalaryController@createSalary',
        ]);
        $api->get('salary/view', [
=======
        $api->post('salary/view', [
>>>>>>> 67ff75c577e71488faeaca240ea2a11a83b7b8e1
            'as' => 'salary.view',
            'uses' => 'SalaryController@viewSalary',
        ]);
    });

});
