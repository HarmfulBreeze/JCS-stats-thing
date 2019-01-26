<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/callback', function (Request $request) {
    $encoded = $request->getContent();
    $json = json_decode($encoded);

    $tournament_code = $json->{'shortCode'};

    return response($tournament_code, 200);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
