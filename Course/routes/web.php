<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PersonalAccountController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::prefix('/rubrics/{rubrics_id}')->group(function () {
    Route::get('/', [IndexController::class, 'rubric'])->where('rubrics_id', '[0-9]+')->name('rubrika');
    Route::get('/{post_id}', [IndexController::class, 'post'])->where(['rubrics_id', '[0-9]+', 'post_id', '[0-9]+'])->name('statya');
    Route::delete('/{post_id}', [AdminController::class, 'destroyPost'])->middleware('auth', 'admin')->where(['rubrics_id', '[0-9]+', 'post_id', '[0-9]+'])->name('delete');
    Route::post('/{post_id}', [PersonalAccountController::class, 'storeReg'])->middleware('auth')->where(['rubrics_id', '[0-9]+', 'post_id', '[0-9]+'])->name('postReg');
    Route::post('/cancel/{post_id}', [PersonalAccountController::class, 'cancelReg'])->middleware('auth')->where(['rubrics_id', '[0-9]+', 'post_id', '[0-9]+'])->name('postCan');
});

Route::get('/registration', [IndexController::class, 'registration'])->name('registration'); ////////
Route::post('/registration', [IndexController::class, 'registerUser'])->name('register-user');////////
Route::get('/login', [IndexController::class, 'login'])->name('login');////////
Route::post('/login', [IndexController::class, 'loginUser'])->name('login-user');////////
Route::get('/logout', [IndexController::class, 'logout'])->name('logout');////////

Route::get('/create', [AdminController::class, 'create'])->middleware('auth', 'admin')->name('create');
Route::post('/create', [AdminController::class, 'store'])->middleware('auth', 'admin')->name('store');

Route::get('/rubric', [AdminController::class, 'createRubric'])->middleware('auth', 'admin')->name('create-rubric');
Route::post('/rubric', [AdminController::class, 'storeRubric'])->middleware('auth', 'admin')->name('store-rubric');

Route::prefix('admin')->middleware('auth', 'admin')->group(
    function () {
        Route::controller(AdminController::class)->group(
            function () {
                Route::get('/', 'index')->name('admin.index');
                Route::get('{id}', 'post')->where(['id', '[0-9]+'])->name('lk.post');
                Route::delete('{id}', 'destroy')->where(['id', '[0-9]+'])->name('admin.destroy');
            }
        );
    }
);


Route::prefix('lk')->middleware('auth')->group(
    function () {
        Route::controller(PersonalAccountController::class)->group(
            function () {
                Route::get('/', 'index')->name('lk.index');
            }
        );
    }
);
