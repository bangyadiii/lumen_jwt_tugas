<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;
use App\Models\User;

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

$router->get('/users', ['middleware' => 'jwt.auth', function () {
    $user = User::all();
    return response()->json([
        "data" => $user
    ]);
}]);
$router->get('/me', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@me']);
$router->post('/login', 'AuthController@authenticate');
