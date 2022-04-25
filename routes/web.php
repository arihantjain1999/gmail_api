<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('index');
})->middleware('auth');
Route::get('/compose', function () {
    return view('gmail.compose');
})->middleware('auth');

Auth::routes();
// Route::get('/label', function () {
    //     return view('gmail.label');
    // })->middleware('auth');
    
Route::get('/label/get-labels',[ LabelController::class,'getMyLabels'])->name('label.get-labels')->middleware('auth');
Route::resource('label', LabelController::class)->middleware('auth');
// Route::resource('gmail',GmailController::class)->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// route for gmail login

Route::get('login/{provider}', [SocialController::class, 'redirect']);
Route::get('login/{provider}/callback', [SocialController::class, 'Callback']);





