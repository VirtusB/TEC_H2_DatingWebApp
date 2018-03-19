<?php
// gør det let at hente konfigurationen vi har sat i init.php
// eksempel: Config::get('sqlsrv/Server'); - returnerer 127.0.0.1
class Config {
    public static function get($path = null) {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach($path as  $bit) {
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}