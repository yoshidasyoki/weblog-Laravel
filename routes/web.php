<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ArticleController;
use App\Http\Middleware\CanViewArticle;

// Route::get('/', function () {
//     return view('welcome');
// });

// トップページ
Route::get('/', [HomeController::class, 'index'])->name('home');

// ログインページ
Route::get('/login', [LoginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [LoginController::class,'login'])->name('login.auth');
Route::get('/logout', [LoginController::class,'logout'])->name('logout');

// 新規登録ページ
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register/create', [RegisterController::class, 'create'])->name('register.create');

// CRUD操作
Route::resource('articles', ArticleController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');
Route::resource('articles', ArticleController::class)
    ->only(['show'])
    ->middleware(CanViewArticle::class);
