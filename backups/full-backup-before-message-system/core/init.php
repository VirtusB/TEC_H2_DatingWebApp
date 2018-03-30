<?php
// init.php inkluderes 1 gang på alle sider, fungerer som en initializer

session_start(); // start sessionen

// sæt vores globale konfiguration
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'virtusbc_h2_user',
        'password' => 'rootpwdating',
        'dbname' => 'virtusbc_tec-dating'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

// auto load vores klasser
spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

// funktion som bruges til at escape strings, beskytter bl.a. mod SQL injection
require_once 'functions/sanitize.php';

// hvis brugeren har en cookie, samt den cookie svarer overens med hashen i databasen, log brugeren ind.
// "husk mig" funktionalitet
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('UserSession', array('hash', '=', $hash));

    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->userID);
        $user->login();
    }
}