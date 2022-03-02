<?php

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
Auth::routes(['verify' => true]);

Route::get('application', 'ApplicationController@create')->name('applications.create');
Route::post('application', 'ApplicationController@store')->name('applications.store');
Route::post('application/attachment/upload', 'ApplicationController@upload')->name('applications.attachment.upload');
Route::get('application/attachment/{attachment}/download', 'ApplicationController@download')
    ->name('applications.attachment.download');
Route::delete('application/attachment/{attachment}/delete', 'ApplicationController@destroy')
    ->name('applications.attachment.delete');

Route::get('jury', 'JuryController@index')->name('jury.index');
Route::get('partners', 'PartnerController@index')->name('partners.index');

Route::get('participants', 'ParticipantsController@index')->name('participant.index');
Route::get('youtube/fix', 'YoutubeStatsController@fixStats')->name('youtube.fix');

Route::get('news', 'NewsController@index')->name('news.index');
Route::get('news/{slug}', 'NewsController@show')->name('news.show');

Route::get('articles', 'ArticleController@index')->name('articles.index');
Route::get('articles/{slug}', 'ArticleController@show')->name('articles.show');

Route::get('galleries', 'GalleryController@index')->name('galleries.index');
Route::get('galleries/{gallery}', 'GalleryController@show')->name('galleries.show');

Route::group(['middleware' => 'auth'], function ($router) {
    $router->get('competition/', "CompetitionController@index")->name('competition.index');
    $router->get('competition/{id}', "CompetitionController@show")->name('competition.show');
    $router->post('competition/set-vote', 'CompetitionController@setVote')->name('competition.set-vote');
    $router->post('competition/unset-vote', 'CompetitionController@unsetVote')->name('competition.unset-vote');

    $router->group(['middleware' => 'verified'], function ($router) {
        $router->post('participants/set-vote/{participant}', 'ParticipantsController@setVote')->name('participant.set-vote');
        $router->post('participants/unset-vote/{participant}', 'ParticipantsController@unsetVote')->name('participant.unset-vote');
    });
});

Route::fallback('PageController@show');
