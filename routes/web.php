<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // Profile routes
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    // Posts routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/my-posts', [PostController::class, 'userPosts'])->name('posts.my');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comments routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Likes routes
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');


    // Admin edit user (updateRole and delete)
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users', [UserController::class, 'index'])->name('user.index');

    Route::get('/manage', [ManageController::class, 'index'])->name('manage');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::put('/manage/users/{user}/role', [ManageController::class, 'updateUserRole'])->name('manage.updateUserRole');
        Route::delete('/manage/users/{user}', [ManageController::class, 'deleteUser'])->name('manage.deleteUser');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');
        Route::post('/categories', [ManageController::class, 'createCategory'])->name('manage.createCategory');
        Route::delete('/manage/categories/{category}', [ManageController::class, 'deleteCategory'])->name('manage.deleteCategory');
    });

});

