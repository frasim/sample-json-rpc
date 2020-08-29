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

$router->post('/', 'JsonRpcController@handleRequest');

$router->get('/test', function () use ($router) {
    return <<<HTML
<form action="/" method="post">
    <input type="text" name="id" value="1">
    <input type="text" name="jsonrpc" value="2.0">
    <input type="text" name="method" value="SearchNearestPharmacy">
    <input type="text" name="params[currentLocation][latitude]" value="41.10938993">
    <input type="text" name="params[currentLocation][longitude]" value="15.0321010">
    <input type="text" name="params[range]" value="5000">
    <input type="text" name="params[limit]" value="2">
    <button type="submit">Invia</button>
</form>
HTML;
});

