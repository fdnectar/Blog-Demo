<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
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
//     return view('front.pages.example');
// });

//initiated git

Route::get('/', function () {
    return view('amazon-seller');
});

Route::view('/blogs', 'front.pages.home')->name('home');

Route::get('/blogs/article/{any}', [BlogController::class, 'readPost'])->name('read_post');
Route::get('/blogs/category/{any}', [BlogController::class, 'categoryPosts'])->name('category_posts');
Route::get('/blogs/post/tag/{any}', [BlogController::class, 'tagPosts'])->name('tag_posts');
Route::get('/blogs/search', [BlogController::class, 'searchBlog'])->name('search_posts');



