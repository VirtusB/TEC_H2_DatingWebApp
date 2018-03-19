<?php
// gør det let at omdirigere 
// da vi har URL rewriting på, skal det være forside i stedet for forside.php
// eksempel: Redirect::to('index.php'); eller Redirect::to(404);
class Redirect {
    public static function to($location = null) {
        if($location) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        exit();
                    break;
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }
}