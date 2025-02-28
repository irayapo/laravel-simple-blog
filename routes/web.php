<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Route untuk menampilkan detail post
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
});
// Route untuk menyimpan post baru
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Route untuk menampilkan form edit post
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

// Route untuk memperbarui post yang sudah ada
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

// Route untuk menghapus post
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');


// Route::get('/posts', function () {
//     return view('posts.index');
// })->name('posts.index');

// Route::get('/posts/create', function () {
//     return view('posts.create');
// })->name('posts.create');

// Route::get('/posts/show', function () {
//     return view('posts.show');
// });

// Route::get('/posts/edit', function () {
//     return view('posts.edit');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
