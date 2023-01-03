<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

	Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
	Route::post('login-all', 'App\Http\Controllers\Api\AuthController@loginAll');
	Route::post('login-social-network', 'App\Http\Controllers\Api\AuthController@loginSocialNetwork');
	Route::post('signup', 'App\Http\Controllers\Api\AuthController@signup');
	Route::post('signup-social-network', 'App\Http\Controllers\Api\AuthController@signupSocialNetwork');
	Route::post('check-email', 'App\Http\Controllers\Api\AuthController@checkEmail');
	Route::get('activate-account/{email}/{token}', 'App\Http\Controllers\Api\AuthController@activate_account');
	Route::post('forgotpassword', 'App\Http\Controllers\Api\AuthController@forgotPassword');
	Route::post('recoverypassword', 'App\Http\Controllers\Api\AuthController@recoveryPassword');
	Route::put('user/{id}/edit-profile', ['uses' => 'App\Http\Controllers\Api\UserController@editPreferences', 'as' => 'user.editprofile']);
	Route::put('user/{id}/change-password', ['uses' => 'App\Http\Controllers\Api\AuthController@changePassword', 'as' => 'user.changepassword']);
	Route::put('user/{id}/change-email-name', ['uses' => 'App\Http\Controllers\Api\UserController@changeEmailName', 'as' => 'user.changeemailname']);
	Route::put('user/{id}/edit-preferences', ['uses' => 'App\Http\Controllers\Api\UserController@editPreferences', 'as' => 'user.editpreferences']);
	Route::put('user/{id}/upload-avatar', ['uses' => 'App\Http\Controllers\Api\UserController@uploadAvatar', 'as' => 'user.uploadavatar']);
	Route::resource('users','App\Http\Controllers\Api\UserController');
	Route::resource('entrytypes','App\Http\Controllers\Api\EntryTypeController');
	Route::resource('languages','App\Http\Controllers\Api\LanguageController');
	Route::resource('newsletters','App\Http\Controllers\Api\NewsletterController');
	Route::resource('tags','App\Http\Controllers\Api\TagController');
	Route::put('unsubscribe-newsletter', ['uses' => 'App\Http\Controllers\Api\SubscribedUserController@unsubscribeNewsletter', 'as' => 'user.unsubscribenewsletter']);
	Route::resource('subscribedusers','App\Http\Controllers\Api\SubscribedUserController');
	Route::get('preferences','App\Http\Controllers\Api\ThemeController@preferences');
	Route::get('init-themes','App\Http\Controllers\Api\ThemeController@initThemes');
	Route::get('content-themes','App\Http\Controllers\Api\ThemeController@contentThemes');
	Route::get('all-themes','App\Http\Controllers\Api\ThemeController@allThemes');
	Route::resource('themes','App\Http\Controllers\Api\ThemeController');
	Route::resource('sections','App\Http\Controllers\Api\SectionController');
	Route::resource('statuses','App\Http\Controllers\Api\StatusController');
	Route::get('entry/{slug}','App\Http\Controllers\Api\EntryController@showBySlug');
	Route::put('entry/{entry_id}/user/{user_id}/like-unlike', ['uses' => 'App\Http\Controllers\Api\EntryController@saveFavoriteEntry', 'as' => 'entry.savefavoriteentry']);
	Route::get('entries/favorites/user/{user_id}','App\Http\Controllers\Api\EntryController@showFavoriteEntriesByUser');
	Route::get('entries/mostvalued','App\Http\Controllers\Api\EntryController@showMostValuedEntries');
	Route::get('entries/mostseen','App\Http\Controllers\Api\EntryController@showMostSeenEntries');
	Route::get('entries/{slug_related_entry}/related','App\Http\Controllers\Api\EntryController@relatedEntries');
	Route::get('entries/user/{user_id}/thoughtforyou','App\Http\Controllers\Api\EntryController@showThoughtForYouEntries');
	Route::put('entries/{id}/update-views-number', ['uses' => 'App\Http\Controllers\Api\EntryController@updateViewsNumber', 'as' => 'entries.updateentryviews']);
	Route::get('load-chapters','App\Http\Controllers\Api\EntryController@insertChapters');
	Route::get('load-chapter-languages','App\Http\Controllers\Api\EntryController@insertChapterLanguages');
	Route::get('user/{user_id}/entrychapters','App\Http\Controllers\Api\EntryController@chaptersByUser');
	Route::put('entrychapter/{entry_chapter_id}/user/{user_id}/current-chapter', ['uses' => 'App\Http\Controllers\Api\EntryController@updateChapterByUser', 'as' => 'entrychapter.updatechapterbyuser']);
	Route::put('entrychapter/{entry_chapter_id}/user/{user_id}/like-unlike', ['uses' => 'App\Http\Controllers\Api\EntryController@saveFavoriteEntryChapter', 'as' => 'entrychapter.savefavoriteentrychapter']);
	Route::resource('entries','App\Http\Controllers\Api\EntryController');
	Route::get('content-templates','App\Http\Controllers\Api\TemplateController@contentTemplates');
	Route::get('init-templates','App\Http\Controllers\Api\TemplateController@initTemplates');

    Route::middleware(['auth:api'])->group(function () {
		Route::get('logout', 'App\Http\Controllers\Api\AuthController@logout');
		Route::get('user', 'App\Http\Controllers\Api\AuthController@user');
    });

	//Limpiar caché
	Route::get('/clear', function() {
	   Artisan::call('cache:clear');
	   Artisan::call('config:clear');
	   Artisan::call('config:cache');
	   Artisan::call('view:clear');
	   return "Cleared!";
	});


	//Test Routes Vimeo

	Route::post('request', [
	    'as' => 'vimeo.request',
	    'uses' => 'App\Http\Controllers\Api\VimeoController@request'
	]);

	Route::post('complete', [
	    'as' => 'vimeo.completeUpload',
	    'uses' => 'App\Http\Controllers\Api\VimeoController@completeUpload'
	]);

	Route::post('update/{videoId}', [
	    'as' => 'vimeo.updateVideoData',
	    'uses' => 'App\Http\Controllers\Api\VimeoController@updateVideoData'
	]);

