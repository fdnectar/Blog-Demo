<?php

use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\AuthorController;

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

Route::prefix('author')->name('author.')->group(function(){
    Route::middleware(['guest:web'])->group(function(){
        Route::view('/login', 'back.pages.auth.login')->name('login');
        Route::view('/forgot-password', 'back.pages.auth.forgot')->name('forgot-password');
        Route::get('/password/reset/{token}', [AuthorController::class, 'ResetForm'])->name('reset-form');
    });

    Route::middleware(['auth:web'])->group(function(){
        Route::get('/home', [AuthorController::class, 'index'])->name('home');
        Route::post('/logout', [AuthorController::class, 'logout'])->name('logout');
        Route::view('/author-profile', 'back.pages.author-profile')->name('author-profile');
        Route::post('/change-profile-picture', [AuthorController::class, 'ChangeProfilePicture'])->name('change-profile-picture');
        
      



        //Only admin can access
        Route::middleware(['isAdmin'])->group(function(){
            Route::view('/settings', 'back.pages.settings')->name('settings');
            Route::post('/change-blog-logo', [AuthorController::class, 'ChangeBlogLogo'])->name('change-blog-logo');
            Route::view('/authors', 'back.pages.authors')->name('authors');
            Route::view('/categories', 'back.pages.categories')->name('categories');
        });

        Route::prefix('posts')->name('posts.')->group(function(){
            Route::view('/add-post', 'back.pages.add-post')->name('add-post');
            Route::post('/create', [AuthorController::class, 'createPost'])->name('create-post');
            Route::view('/all-posts', 'back.pages.all-posts')->name('all-posts');
            Route::get('/edit-post', [AuthorController::class, 'editPost'])->name('edit-post');
            Route::post('/update-post', [AuthorController::class, 'updatePost'])->name('update-post');
        });
    });
});
