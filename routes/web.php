<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
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

Route::get('/label/get-labels', [LabelController::class, 'getMyLabels'])->name('label.get-labels')->middleware('auth');
Route::get('/label/deletemail', [LabelController::class, 'deletemail'])->name('label.deletemail')->middleware('auth');
Route::get('/label/starredmail', [LabelController::class, 'starredmail'])->name('label.starredmail')->middleware('auth');
Route::post('/label/sendmail', [LabelController::class, 'sendmail'])->name('label.sendmail')->middleware('auth');
Route::get('/label/scearch', [LabelController::class, 'scearch'])->name('label.scearch')->middleware('auth');
Route::get('/label/showUser',[ LabelController::class,'showUser'])->name('label.showUser')->middleware('auth');
Route::resource('label', LabelController::class)->middleware('auth');
Route::resource('user',UserController::class)->middleware('auth');
Route::get('editUser/{id}',[userController::class,'editUser'])->name('user.editUser')->middleware('auth');
// Route::resource('gmail',GmailController::class)->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// route for gmail login


Route::get('login/{provider}', [SocialController::class, 'redirect']);
Route::get('login/{provider}/callback', [SocialController::class, 'Callback']);
