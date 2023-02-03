<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [ApiController::class, 'login']);
Route::post('logout', [ApiController::class, 'logout']);
Route::post('change-password', [ApiController::class, 'changePassword']);

Route::get('get-program',[ApiController::class, 'getProgram']);
Route::post('post-program',[ApiController::class, 'postProgram']);
Route::post('post-documentation-program',[ApiController::class, 'postDocumentationProgram']);

