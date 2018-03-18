<?php
session_start();

$GLOBALS['config'] = array(
    'sqlsrv' => array(
        'Server' => '127.0.0.1',
        'UserName' => 'sa',
        'Password' => 'Virtus13',
        'Database' => 'OOP_Login_Register'
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

// auto load of classes
spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('UserSession', array('hash', '=', $hash));

    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->userID);
        $user->login();
    }
}