<?php
// giver os mulighed for at generere en sha256 kryptering, en salt og et unikt id
// vi bruger kun unique(), da PHP har introduceret password_hash() som er langt mere sikkert og inkluderer en salt direkte i password
class Hash {
    public static function make($string, $salt = '') {
        return hash('sha256', $string . $salt);
    }

    public static function salt($length) {
        return random_bytes($length);
    }

    public static function unique() {
        return self::make(uniqid());
    }
}