<?php
// klasse som bruges til alt med inputs, f.eks. et input hvor brugeren skal taste deres password
// eksempel: Input::get('password'); - returnerer brugerns password hvis det er sat, og hvis inputtet navn er password
class Input {
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
            break;
            case 'get':
            return (!empty($_GET)) ? true : false;
            break;
            default:
                return false;
            break;
        }
    }

    public static function get($item) {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }


}