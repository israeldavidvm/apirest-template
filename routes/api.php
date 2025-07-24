<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController as AuthControllerV1;
use App\Http\Controllers\Api\V2\AuthController as AuthControllerV2;





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


Route::prefix('/v1')->group(function () {

    #Begin of sandbox api endpoints

    
    
    #Begin of production api endpoints

    Route::prefix('/auth')->group(function () {

            Route::post('login', [AuthControllerV1::class, 'login']);
    
            Route::middleware(['auth:sanctum'])->group(function () {
    
                Route::post('logout', [AuthControllerV1::class, 'logout']);
                Route::post('me', [AuthControllerV1::class, 'me']);
                
                Route::post('register',[
                    AuthControllerV1::class,
                    'register'
                ]);
                    
            });
    
    });

    Route::middleware(['auth:sanctum'])->group(function () {

    });
    

});


Route::prefix('/v2')->group(function () {

    #Begin of sandbox api endpoints
    
    
    #Begin of production api endpoints

    Route::prefix('/auth')->group(function () {

            Route::post('login', [AuthControllerV2::class, 'login']);
    
            Route::middleware(['auth:sanctum'])->group(function () {
    
                Route::post('logout', [AuthControllerV2::class, 'logout']);
                Route::post('me', [AuthControllerV2::class, 'me']);
                
                Route::post('register',[
                    AuthControllerV2::class,
                    'register'
                ]);
                    
            });
    
    });

    // Route::middleware(['auth:sanctum'])->group(function () {

  
    // });
    

});