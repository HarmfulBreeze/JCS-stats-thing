<?php

use Illuminate\Http\Request;
use Carbon\Carbon;

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
    $json = json_decode($request->getContent());

    $tournament_code = $json->{'shortCode'};
    $game_id = $json->{'gameId'};
    $timestamp = Carbon::createFromTimestampMs($json->{'startTime'}, 'Europe/Paris')->format('d/m/Y H:i');

    // On évite d'avoir la même game qui s'ajoute plusieurs fois
    $query = DB::raw(sprintf("IF NOT EXISTS (SELECT * FROM games WITH (TABLOCKX) WHERE game_id = %s) INSERT INTO games WITH (TABLOCKX) ([game_id], [tournament_code], [timestamp]) VALUES (%s, '%s', '%s')", $game_id, $game_id, $tournament_code, $timestamp));
    DB::setDatabaseName('jcs-stats');
    DB::insert($query);

    return response()->noContent(200);
});

Route::get('/gameinfo', function () {
    $info = DB::table('games')->select('game_id', 'tournament_code', 'timestamp')->get();
    return response()->json($info);
});

Route::post('/deletegameinfo', function (Request $request) {
    $json = json_decode($request->getContent());

    $game_id = $json->{'gameId'};

    $success = DB::table('games')->where('game_id', $game_id)->delete();

    if ($success === 0) {
        return response()->noContent(404);
    }
    return response()->noContent(200);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
