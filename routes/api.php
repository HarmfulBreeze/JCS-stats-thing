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
    $game_id = $json->{'gameId'};
    DB::table('games')->insert(['game_id' => $game_id, 'tournament_code' => $tournament_code]);

    return response($tournament_code, 200);
});

Route::get('/gameinfo', function () {
    $info = DB::table('games')->select('game_id', 'tournament_code')->get();
    return response()->json($info);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
