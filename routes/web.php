<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ArticleController;
use App\Http\Middleware\AuthorizeArticleView;
use App\Models\Article;

// Route::get('/', function () {
//     return view('welcome');
// });

// トップページ
Route::get('/', [HomeController::class, 'index'])->name('home');

// ログインページ
Route::get('/login', [LoginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// 新規登録ページ
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register')
    ->middleware('guest');
Route::post('/register/create', [RegisterController::class, 'create'])->name('register.create');

// CRUD操作
Route::get('articles/trash', [ArticleController::class, 'trash'])->name('articles.trash')->middleware('auth');
Route::get('articles/{article}/trash', [ArticleController::class, 'rummage'])->name('articles.rummage')->middleware('auth');
Route::patch('articles/{article}/restore', [ArticleController::class, 'restore'])->name('articles.restore')->middleware('auth');
Route::delete('articles/{article}/forceDelete', [ArticleController::class, 'forceDelete'])->name('articles.forceDelete')->middleware('auth');

Route::resource('articles', ArticleController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');
Route::resource('articles', ArticleController::class)
    ->only(['show']);
