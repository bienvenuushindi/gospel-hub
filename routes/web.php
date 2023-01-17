<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
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
Route::view('/', 'home');
Route::view('/author', 'author');
Route::view('/authors', 'authors');
Route::view('/author_articles', 'author_articles');
Route::view('/audio', 'audio');
Route::view('/article', 'article');
Route::view('/contact_us', 'contact');
Route::post('/contact_us', 'HomeController@store')->name('home.store');

Auth::routes(['register' => true]);
Route::get('/events', 'HomeController@viewEvents')->name('events');
Route::get('/tag/{id}', 'TagController@show');
Route::get('/comments/post/{id}/{visitor?}', 'PostCommentController@index');
Route::get('/create/post/audio', 'PostController@createAudio')->name('audio.create');
Route::get('/posts/{postType}', 'PostController@listAll')->name('post.display');
Route::get('/user/account', 'HomeController@account')->name('account');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/article/{id}/{slug?}', 'HomeController@viewArticle')->name('article');
Route::get('/audio/{id}/{slug?}', 'HomeController@viewAudio')->name('audio');
Route::get('/author/{id}', 'HomeController@viewAuthor')->name('author');
Route::get('/page/posts/{type}', 'HomeController@viewPosts')->name('posts');
Route::get('/authors', 'HomeController@viewAuthors')->name('posts.authors');
Route::get('/become_teacher', 'HomeController@createTeacher')->name('create_teacher');
Route::post('/emailSubscription', 'HomeController@emailSubscription')->name('emailSubscription');
Route::get('/download/{id}', 'HomeController@downloadAudio')->name('download');
Route::get('/post/delete/{id}','PostController@delete')->name('post.delete');
Route::get('/teachings/{date}/{month?}','TeachingController@fetch')->name('teaching.date');
Route::get('/teaching/delete/{id}','TeachingController@delete')->name('teaching.delete');
Route::get('/posts/{postType}/{year}/{user?}/{month?}','PostController@fetch')->name('post.date');
Route::resource('teaching', 'TeachingController');
Route::resource('comment', 'PostCommentController')->except('index');
Route::resource('post', 'PostController');
Route::resource('event', 'EventController');
Route::resource('tag', 'TagController')->middleware('auth')->except('show');
Route::resource('profile', 'UserController')->middleware('auth');

//Admin Routes
Route::middleware(['admin'])->group(function (){

    Route::prefix('admin')->group(function () {
        Route::get('authors/{status?}', 'AdminController@authors')->name('authors.status');
        Route::get('authors/update/status/{status}/user/{user}', 'AdminController@updateUserStatus')->name('author.update.status');
        Route::get('authors/update/user/{user}', 'AdminController@edit')->name('author.status.edit');
        Route::get('roles','AdminController@role');
        Route::get('subscription','AdminController@email_subscription')->name('admin.email_subscription');
        Route::get('subscription/update/status/{status}/user/{user}', 'AdminController@updateSubscriptionStatus')->name('subscription.update.status');
        Route::get('subscription/update/user/{user}', 'AdminController@editSubscription')->name('subscription.status.edit');
        Route::get('author/posts/{id}','AdminController@authorPosts')->name('admin.author.posts');
        Route::get('author/posts/{postType}/{year}/{user?}/{month?}','AdminController@authorFetch')->name('admin.author.post.date');
        Route::get('post-types','AdminController@postType');
    });
    Route::resource('admin', 'AdminController');
});
Route::any('{slug}','HomeController@index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
