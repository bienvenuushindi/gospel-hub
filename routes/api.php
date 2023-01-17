<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTypeController;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
Route::get('posts/{postType}',function ($postType){
    if ($postType === "audios") {
        $posts=PostController::getPopularAudios()->paginate(12);
    } elseif ($postType === "articles") {
        $posts=PostController::getArticles()->paginate(12);
    };

    return json_encode($posts);
});
Route::get('authors/',function (){
    $authors= HomeController::getAuthors()->paginate(12);
    return json_encode( $authors);

});
Route::get('events/',function (){
    return json_encode(EventController::getAllEvents());
});
Route::get('author/{id}',function ($id){
    return json_encode( ['data' => $author = User::query()->find($id), 'postTypeAudio' => PostTypeController::typeAudio()]);
});
Route::get('article/{id}',function ($id){
    return json_encode(['post' => PostController::getClientSidePost($id)['post'], 'relatedPost' => PostController::getClientSidePost($id)['related']]);
});
Route::get('audio/{id}',function ($id){
    return json_encode(['post' => PostController::getClientSidePost($id)['post'], 'relatedPost' => PostController::getClientSidePost($id)['related']]);
});
Route::post('login', 'Auth\LoginController@login');





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
