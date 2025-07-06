<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class,'Index']);
Route::delete('/{id}',[IndexController::class,'delete'])->name('delete');
Route::put('/update/{id}', [IndexController::class, 'update'])->name('update.confirm');
Route::get('/update/{id}', [IndexController::class, 'openEdit'])->name('update');
Route::get('/create', [IndexController::class, 'openCreate'])->name('create');
Route::put('/create', [IndexController::class, 'create'])->name('create.confirm');

Route::get('/firstQuery',[IndexController::class,'getFiveToFift']);
Route::get('/secondQuery',[IndexController::class,'getFioStage']);
Route::get('/thirdQuery',[IndexController::class,'getTotalQuan']);
Route::get('/fourthQuery',[IndexController::class,'getProfHas']);