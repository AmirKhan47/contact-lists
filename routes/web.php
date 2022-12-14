<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('categories', \App\Http\Controllers\UserCategoryController::class);
    Route::resource('contacts', \App\Http\Controllers\UserContactController::class);

    // import csv of contacts, send sms and store in database
    Route::post('import', [\App\Http\Controllers\UserContactController::class, 'import']);
});
