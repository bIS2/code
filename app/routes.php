<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------
 */

// Route::group(['prefix' => LaravelLocalization::setLanguage(),'before' => 'LaravelLocalizationRedirectFilter'], function() {
Route::get('language/{lang}', function($lang){
    App::setLocale($lang);
    return Redirect::to('/');
});

	Route::model('user', 'User');
	Route::model('comment', 'Comment');
	Route::model('post', 'Post');
	Route::model('role', 'Role');

	Route::group(array( 'before' => ['auth']), function(){


		# Index Page - Last route, no matches
		Route::get('/', ['uses' => 'Pages@getIndex']);
		
		Route::controller('pages','Pages');

		Route::resource('admin/libraries', 'LibrariesController' );
		Route::resource('admin/tags', 'TagsController');
		Route::resource('admin/traces', 'TracesController');

		Route::resource('groups', 'GroupsController');
		Route::controller('groups', 'GroupsController');


        Route::resource('holdings', 'HoldingsController');
		Route::controller('holdings', 'HoldingsController');

		Route::resource('sets', 'HoldingssetsController');
		Route::controller('sets', 'HoldingssetsController');

		Route::controller('lists', 'HlistsController');
		Route::resource('lists', 'HlistsController');

		Route::resource('notes', 'NotesController');
		Route::resource('reserves', 'ReservesController');

		Route::resource('oks', 'OksController');

		Route::resource('traces', 'TracesController');

		Route::when('sets*', 'auth_like_librarian');
		// Route::when('holdings*', 'auth_like_storeman');

        Route::when('admin/roles*', 'admin_roles');
		Route::when('admin/users*', 'admin_users');
        
        Route::controller('external', 'ExternalController');
        Route::resource('external', 'ExternalController');


	});
// }); // localization


/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */
Route::group(array('prefix' => 'admin'), function()
{
    # User Management
    Route::get('users/{user}/show', 'AdminUsersController@getShow')
        ->where('user', '[0-9]+');
    Route::get('users/{user}/edit', 'AdminUsersController@getEdit')
        ->where('user', '[0-9]+');
    Route::post('users/{user}/edit', 'AdminUsersController@postEdit')
        ->where('user', '[0-9]+');
    Route::get('users/{user}/delete', 'AdminUsersController@getDelete')
        ->where('user', '[0-9]+');
    Route::post('users/{user}/delete', 'AdminUsersController@postDelete')
        ->where('user', '[0-9]+');
    Route::controller('users', 'AdminUsersController');

    # User Role Management
    Route::get('roles/{role}/show', 'AdminRolesController@getShow')
        ->where('role', '[0-9]+');
    Route::get('roles/{role}/edit', 'AdminRolesController@getEdit')
        ->where('role', '[0-9]+');
    Route::post('roles/{role}/edit', 'AdminRolesController@postEdit')
        ->where('role', '[0-9]+');
    Route::get('roles/{role}/delete', 'AdminRolesController@getDelete')
        ->where('role', '[0-9]+');
    Route::post('roles/{role}/delete', 'AdminRolesController@postDelete')
        ->where('role', '[0-9]+');

    Route::controller('roles', 'AdminRolesController');

    # Admin Dashboard
    Route::controller('/', 'AdminDashboardController');

});


/** ------------------------------------------
 *  Frontend Routes
 *  ------------------------------------------
 */

// User reset routes
Route::get('user/reset/{token}', 'UserController@getReset')
    ->where('token', '[0-9a-z]+');

// User password reset
Route::post('user/reset/{token}', 'UserController@postReset')
    ->where('token', '[0-9a-z]+');

//:: User Account Routes ::
Route::post('user/{user}/edit', 'UserController@postEdit')
    ->where('user', '[0-9]+');

//:: User Account Routes ::
Route::post('user/login', 'UserController@postLogin');

# User RESTful Routes (Login, Logout, Register, etc)
Route::controller('user', 'UserController');







Route::resource('deliveries', 'DeliveriesController');

Route::resource('confirms', 'ConfirmsController');

<<<<<<< HEAD
<<<<<<< HEAD
Route::resource('lockeds', 'LockedsController');
=======
Route::resource('reviseds', 'RevisedsController');
>>>>>>> 86da6699d295af34ca3376881e7d69712455ac23
=======
Route::resource('reviseds', 'RevisedsController');

Route::resource('lockeds', 'LockedsController');
>>>>>>> 244bf5e537375d6bff2d7e12b5a44c93e8936705
