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

Route::get('/', [IndexController::class,'welcome'])->name('welcome');
Route::get('/admin', [IndexController::class,'admin'])->name('admin');
Route::get('/rubrics/{id}', [IndexController::class,'filtered'])->name('filtered');
Route::get('/cab/{login}', [IndexController::class,'lk'])->middleware('auth')->name('lk');
Route::get('/article/{id}/{user_id?}', [IndexController::class,'in'])->name('in');
Route::delete('/admin/{id}',[IndexController::class,'delete'])->name('delete');
Route::get('/admin/create',[IndexController::class,'openCreate'])->name('create');
Route::put('/admin/create', [IndexController::class, 'add'])->name('create.confirm');
Route::put('/admin', [IndexController::class, 'addLan'])->name('addLan');
Route::delete('/admin/record/{id}', [IndexController::class, 'deleteCourseUser'])->name('deleteCourseUser');
Route::delete('/article/{id}', [IndexController::class, 'unsub'])->middleware('auth')->name('unsub');
Route::post('/article/{id}', [IndexController::class, 'sub'])->middleware('auth')->name('sub');
Route::post('/article/cancel/{course_id}', [IndexController::class, 'cancelReg'])->middleware('auth')->name('postCan');

Auth::routes();

#Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
