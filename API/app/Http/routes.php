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
// define(ROUTE_BASE, 'API/public');
$app->get('/version', function () use ($app) {
	return $app->version();
});
$app->get('/index', 'IndexController@index');

$app->group(['middleware' => 'check', 'prefix' => 'v1', 'namespace' => 'App\Http\Controllers\V1'], function ($app) {
	$app->get('/eleme', 'TestController@eleme');
	$app->get('/dada', 'TestController@dada');
	$app->get('/dadaback', 'TestController@dadaback');
});
