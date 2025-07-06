<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\AuthController;
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

Route::get('/article/{id}', [IndexController::class,'in'])->name('in');
Route::delete('/{id}',[IndexController::class,'delete'])->name('delete');
Route::get('/create',[IndexController::class,'openCreate'])->name('create');
Route::put('/create', [IndexController::class, 'add'])->name('create.confirm');
Route::put('/', [IndexController::class, 'addCat'])->name('addCat');


Auth::routes();
Route::get('/{sorted?}', [IndexController::class,'welcome'])->name('welcome');

#Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
