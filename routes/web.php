<?php

use App\Http\Controllers\web\homeController;
use App\Http\Controllers\web\loginController;
use App\Http\Controllers\web\beritaController;
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
Route::get('/', [homeController::class,'show'])->name('login');
Route::get('/profile', [homeController::class, 'profile']);
Route::post('/register', [homeController::class, 'registerPost']);
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [loginController::class, 'logout']);

// jika sudah login, maka ini route akan bisa diakses, kalo ndk login ya sudahlah
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard',[homeController::class, 'dashboard']);
    Route::get('/user',[homeController::class, 'user']);
    Route::post('/user/simpan',[homeController::class, 'userPost']);
    Route::get('/user/edit/{id}',[homeController::class, 'userEdit']);
    Route::delete('/user/hapus/{id}',[homeController::class, 'userHapus']);

    Route::get('/berita',[beritaController::class, 'index']);
    Route::post('/berita/simpan',[beritaController::class, 'beritaSimpan']);
    Route::post('/berita/simpanGambar',[beritaController::class, 'beritaGambar']);
    Route::get('/berita/edit/{id}', [beritaController::class, 'beritaEdit']);
    Route::delete('/berita/delete/{id}', [beritaController::class, 'beritaDelete']);
});
