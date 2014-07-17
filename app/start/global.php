<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/
require app_path().'/translation.php';

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/presenters',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
App::error(function(Exception $exception, $code)
{
    $pathInfo = Request::getPathInfo();
    $message = $exception->getMessage() ?: 'Exception';
    Log::error("$code - $message @ $pathInfo\r\n$exception");
    
    if (Config::get('app.debug')) {
    	return;
    }
    $mails = [ 'asleyarbolaez@gmail.com', 'piguet@trialog.ch', 'soto.platero@gmail.com' ];
    // $mails[] = 'asleyarbolaez@gmail.com';
    // $mails[] = 'piguet@trialog.ch';

    $data = [ 'exception' => $exception, 'url'=> Request::url() ];
    // Mail::pretend(TRUE);

    switch ($code)
    {
        case 403:
            return Response::view('error/403', array(), 403);

        case 500:

            foreach ($mails as $mail) {
                Mail::queue('emails/error500', $data, function($message) use ($mail) {
                    $message->to($mail)->subject('An error has ocurred in bIS Project');
                });
            }
            return Response::view('error/500', array(), 500);

            break;
        default:
            foreach ($mails as $mail) {
                Mail::queue('emails/error404', $data, function($message) use ($mail) {
                    $message->to($mail)->subject('Error 404 has ocurred in bIS Project');
                });
            }
            return Response::view('error/404', array(), $code);
            break;
    }
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require __DIR__.'/../filters.php';

