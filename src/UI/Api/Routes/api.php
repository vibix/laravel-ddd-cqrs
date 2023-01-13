<?php

declare(strict_types=1);

use UI\Api\Controllers\v1\Accounts\AccountsListController;
use UI\Api\Controllers\v1\Accounts\VerifyEmailController;
use UI\Api\Controllers\v1\Accounts\LoginController;
use UI\Api\Controllers\v1\Accounts\LogoutController;
use UI\Api\Controllers\v1\Accounts\RegisterController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', static fn () => ['message' => 'Welcome']);

Route::group(['prefix' => '/v1'], static function () {
    Route::group(['prefix' => '/accounts'], static function () {
        Route::post('/register', RegisterController::class);
        Route::post('/login', LoginController::class);
        Route::post('/verify-email/{id}', VerifyEmailController::class);

        Route::middleware('auth:api')->post('/logout', LogoutController::class);
        Route::middleware('auth:api')->get('/', AccountsListController::class); // test only
    });
});
