<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DisturbanceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/test/{id}', [TestController::class,'Test']); не сработает!
Route::get('/test/{id}', [DisturbanceController::class,'Dist']);
