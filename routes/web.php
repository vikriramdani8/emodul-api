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

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/logout', 'AuthController@logout');

    $router->get('/dashboard', 'EmodulController@dashboard');

    // Prodi
    $router->get('/prodi', 'ProdiController@index');
    $router->get('/prodi/detail/{id}', 'ProdiController@show');
    $router->get('/prodi/{slug}', 'ProdiController@showBySlug');
    $router->post('/prodi', 'ProdiController@store');
    $router->put('/prodi/{id}', 'ProdiController@update');
    $router->delete('/prodi/{id}', 'ProdiController@delete');

    // Matakuliah
    $router->get('/matakuliah', 'MatakuliahController@index');
    $router->get('/matakuliah/detail/{id}', 'MatakuliahController@show');
    $router->get('/matakuliah/{slug}', 'MatakuliahController@showBySlug');
    $router->post('/matakuliah', 'MatakuliahController@store');
    $router->put('/matakuliah/{id}', 'MatakuliahController@update');
    $router->delete('/matakuliah/{id}', 'MatakuliahController@delete');

    // User
    $router->get('/users', 'UserController@index');
    $router->get('/users/{id}', 'UserController@show');
    $router->post('/users', 'UserController@store');
    $router->put('/users/{id}', 'UserController@update');
    $router->delete('/users/{id}', 'UserController@delete');
    $router->put('/reset-password/{id}', 'UserController@resetPassword');

    // Emodul
    $router->get('/emodul', 'EmodulController@index');
    $router->get('/emodul/{slug}', 'EmodulController@showBySlug');
    $router->post('/emodul', 'EmodulController@store');
    $router->delete('/emodul/{id}', 'EmodulController@delete');
});