<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->group(['prefix' => 'probe'], function () use ($router) {
        $router->get('{id}',  ['uses' => 'ProbeController@get']);
        $router->post('',  ['uses' => 'ProbeController@store']);
        $router->get('user/{id}', ['uses' => 'ProbeController@getUser']);
        $router->put('{id}/toggle', ['uses' => 'ProbeController@toggleState']);
    });

    $router->group(['prefix' => 'measure'], function () use ($router) {
        $router->get('probe/{id}_limit={limit}',  ['uses' => 'MeasureController@probe']);
        $router->post('',  ['uses' => 'MeasureController@store']);
    });

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('connect',  ['uses' => 'UserController@connect']);
        $router->post('logout',  ['uses' => 'UserController@logout']);
        $router->post('register',  ['uses' => 'UserController@register']);
        $router->get('infos/{id}',  ['uses' => 'UserController@get']);
    });

});
