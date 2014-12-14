<?php
/*
|--------------------------------------------------------------------------
| Translations
|--------------------------------------------------------------------------
|
| This script gets the locale of user and load the appropriate file 
| that contain the translations.
| 
| English File: /app/lang/en/{section}.php
| Spanish File: /app/lang/es/{section}.php
|
| @Author: Juan Mauricio Escobar Torres.
| @Email: juanmauricioescobar@gmail.com
| @Web: http://www.juanescobar.us
| @Date: September 2013.
| @License: All Rights Reserved.
|
*/

// Set locales allowed.

$localesAllowed = array('en', 'de');

// Set default language.
// It should matchs with $localesAllowed array.

define('DEFAULT_LANG', 'en');

// Set default language if lang session do not exits.

if (!Session::has('locale')) {
  if (isset($_COOKIE['prefered_lang'])) {
  	Session::put('locale', $_COOKIE['prefered_lang']);
  }
  else {
  	Session::put('locale', DEFAULT_LANG);
  }   
}

// Forcing to change language passing the locale via GET.

if (Input::has('lang'))
    if (in_array(Input::get('lang'), $localesAllowed)) {    
        Session::put('locale', Input::get('lang'));
        setcookie('prefered_lang',Input::get('lang'), time() + (864000 * 30));
        // die(Request::header('referer'));
        // return Redirect::to(Request::header('referer'));
        header('location: '.Request::header('referer'));
        die();
    }
    // else     
    //     Session::put('locale', DEFAULT_LANG);

// Overwrite locale in /app/config/app.php file.
// Make translation

App::setLocale(Session::get('locale'));