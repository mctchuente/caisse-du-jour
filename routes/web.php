<?php

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

Route::get('/', function () {
    //return view('welcome');
	return redirect()->route('encaissement.index');
});

Auth::routes();

/**
 *  Route to avoid registration and remove link on required page
 */
Route::get('/register', function () {
    return redirect()->route('encaissement.index');
});

/**
 *  Route to avoid password reset and remove link on login page
 */
Route::get('/password/reset', function () {
    return redirect()->route('encaissement.index');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('home');
/**
 *  Auth Routes
 */
Route::group(['middleware' => ['auth']], function () {
    Route::resource('encaissement', App\Http\Controllers\EncaissementController::class);
});
