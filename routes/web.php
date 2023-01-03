<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\PageLayoutController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

// Routes Enviroment Nebolus

/*
|--------------------------------------------------------------------------
| Autenticación Web
|--------------------------------------------------------------------------
*/

Route::get('/', [ViewsController::class, 'login'])->name('login');
Route::get('forgot-password', [ViewsController::class, 'forgot_password'])->name('forgot-password');
Route::get('reset-password/{email}/{token}', [ViewsController::class, 'reset_password'])->name('reset-password');
Route::post('login', [AuthController::class, 'loginweb'])->name('login');
Route::post('logout', [AuthController::class, 'logoutweb'])->name('logout')->middleware('auth');
Route::post('forgotpassword', [AuthController::class, 'forgotPasswordWeb'])->name('forgotpassword');
Route::post('newpassword', [AuthController::class, 'newPasswordWeb'])->name('newpassword');
/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\UserController::class, 'getJson'])->name('users.get-json');
    Route::get('profile', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
    Route::get('change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change-password');
    Route::put('update-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('users.update-password');
    Route::put('update-profile/{id}', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.update-profile');
});
Route::resource('users', App\Http\Controllers\UserController::class )->middleware('auth');

Route::group(['prefix' => 'roles', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\RolesController::class, 'getListJson'])->name('roles.get-json');
});

Route::group(['prefix' => 'posts-types', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\PostsTypesController::class, 'getListJson'])->name('posts-types.get-json');
});

Route::group(['prefix' => 'themes', 'middleware' => 'auth'], function () {
    Route::get('getListJson', [App\Http\Controllers\ThemesController::class, 'getListJson'])->name('themes.get-list-json');
    Route::get('get-json', [App\Http\Controllers\ThemesController::class, 'getJson'])->name('themes.get-json');
    Route::get('getSlug', [App\Http\Controllers\ThemesController::class, 'getSlug'])->name('themes.get-slug');
});
Route::resource('themes', App\Http\Controllers\ThemesController::class )->middleware('auth');

Route::group(['prefix' => 'posts', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\PostsController::class, 'getJson'])->name('posts.get-json');
    Route::get('getSlug', [App\Http\Controllers\PostsController::class, 'getSlug'])->name('posts.get-slug');
    Route::get('changeStatus/{post}/{status}', [App\Http\Controllers\PostsController::class, 'changeStatus'])->name('posts.change-status');
});
Route::resource('posts', App\Http\Controllers\PostsController::class )->middleware('auth');

Route::group(['prefix' => 'templates', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\TemplatesController::class, 'getJson'])->name('templates.get-json');
    Route::get('getSlug', [App\Http\Controllers\TemplatesController::class, 'getSlug'])->name('templates.get-slug');
});
Route::resource('templates', App\Http\Controllers\TemplatesController::class )->middleware('auth');

Route::group(['prefix' => 'tags', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\TagsController::class, 'getJson'])->name('tags.get-json');
    Route::get('getSlug', [App\Http\Controllers\TagsController::class, 'getSlug'])->name('tags.get-slug');
    Route::get('getListJson', [App\Http\Controllers\TagsController::class, 'getListJson'])->name('tags.get-list-json');
});
Route::resource('tags', App\Http\Controllers\TagsController::class )->middleware('auth');

Route::group(['prefix' => 'templates-init', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\TemplatesInitController::class, 'getJson'])->name('templates-init.get-json');
    Route::get('getSlug', [App\Http\Controllers\TemplatesInitController::class, 'getSlug'])->name('templates-init.get-slug');
});
Route::resource('templates-init', App\Http\Controllers\TemplatesInitController::class )->middleware('auth');

Route::group(['prefix' => 'sections', 'middleware' => 'auth'], function () {
    Route::get('getJson', [App\Http\Controllers\SectionsController::class, 'getJson'])->name('sections.get-json');
});

Route::group(['prefix' => 'status', 'middleware' => 'auth'], function () {
    Route::get('get-json', [App\Http\Controllers\StatusController::class, 'getJson'])->name('status.get-json');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Tablero inicial)
|--------------------------------------------------------------------------
*/
Route::get('dashboard', [ViewsController::class, 'dashboard'])->name('dashboard')->middleware('auth');

/* Only Route Template */

/* Only Route Template */
