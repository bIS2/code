<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) {
        Session::put('loginRedirect', Request::url());
        return Redirect::to('user/login/');
    }
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (!Auth::check()) return Redirect::to('user/login/');
});

/*
|--------------------------------------------------------------------------
| Role Permissions
|--------------------------------------------------------------------------
|
| Access filters based on roles.
|
*/

// Check for role admin o librarian in admin routes
// Entrust::routeNeedsRole( 'admin*', ['speiuser'], Redirect::to('/')->with('info',trans('messages.to_be_admin')), false );
// Entrust::routeNeedsRole( 'holdings*', ['magvuser','maguser'], Redirect::to('/')->with('flash',trans('messages.manage_holdings')), false );
// Entrust::routeNeedsRole( 'sets*', ['bibuser','superuser'], Redirect::to('/')->with('flash',trans('messages.manage_sets')), false );


Route::filter('auth_like_librarian', function(){

    if (!Entrust::hasRole('bibuser') && !Entrust::hasRole('resuser') ) 
        return Redirect::to('/')->with('info',trans('messages.auth_like_librarian'));

});
Route::filter('auth_like_storeman', function(){

    if ( !Authority::can('work','Holding') && !Entrust::hasRole('bibuser') )
        return Redirect::to('/')->with('info',trans('messages.auth_like_storeman'));


    // if ( !Entrust::hasRole('maguser') && !Entrust::hasRole('magvuser') && !Entrust::hasRole('postuser')) 

});
Route::filter('admin_roles', function(){

    if (! Entrust::hasRole('sysadmin') ) 
        return Redirect::to('/')->with('info',trans('messages.auth_like_admin'));

});

Route::filter('admin_users', function(){

    if ( !Entrust::hasRole('sysadmin') and !Entrust::hasRole('superuser') ) 
        return Redirect::to('/')->with('info',trans('messages.auth_like_admin'));

});

// Check for permissions on admin actions
/*Entrust::routeNeedsPermission( 'admin/users*', ['admin','bibuser'], Redirect::to('/admin'), false );
Entrust::routeNeedsPermission( 'admin/roles*', 'admin', Redirect::to('/admin'), false );
*/
/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::getToken() != Input::get('csrf_token') &&  Session::getToken() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| Language
|--------------------------------------------------------------------------
|
| Detect the browser language.
|
*/

/*Route::filter('detectLang',  function($route, $request, $lang = 'auto')
{

    if($lang != "auto" && in_array($lang , Config::get('app.available_language')))
    {
        Config::set('app.locale', $lang);
    }else{
        $browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',') : '';
        $browser_lang = substr($browser_lang, 0,2);
        $userLang = (in_array($browser_lang, Config::get('app.available_language'))) ? $browser_lang : Config::get('app.locale');
        Config::set('app.locale', $userLang);
        App::setLocale($userLang);
    }
});*/
