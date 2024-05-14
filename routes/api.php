<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MessageController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('logged_in')->get('/test', function(Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'message',
    'middleware' => 'logged_in',
    'controller' => MessageController::class
], function () {
    Route::get('{group_chat_id', 'index');
    Route::post('', 'store');
});