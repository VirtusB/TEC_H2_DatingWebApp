<?php
// klasse som bruges til alt med inputs, f.eks. et input hvor brugeren skal taste deres password
// eksempel: Input::get('password'); - returnerer brugerns password hvis det er sat, og hvis inputtet navn er password
class Input
{
    public static function exists($type = 'post')
    {
        switch ($type) {
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

    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }

    public static function getImage($value) {
        if (isset($_FILES[$value]['tmp_name']) && !empty($_FILES[$value]['tmp_name'])) {
            $fileTmpName = $_FILES[$value]['tmp_name'];
            $image = file_get_contents($fileTmpName);
            $encoded_image = base64_encode($image);
            if (strlen($encoded_image) == 0 || $encoded_image == null) {
                return false;
            } else {
                return (string)$encoded_image;
            }
            
        } 
        
    }

    public static function getDate($value)
    {
        $noCommas = str_replace(',', '', $value); // fjern kommaer fra datoen
        $withDashes = str_replace(' ', '-', $noCommas); // erstart mellemrum med bindestreg
        $month = '';

        if (preg_match('/\bJanuar\b/', $withDashes)) {
            $month = str_replace('Januar', '01', $withDashes);
        } else if (preg_match('/\bFebruar\b/', $withDashes)) {
            $month = str_replace('Februar', '02', $withDashes);
        } else if (preg_match('/\bMarts\b/', $withDashes)) {
            $month = str_replace('Marts', '03', $withDashes);
        } else if (preg_match('/\bApril\b/', $withDashes)) {
            $month = str_replace('April', '04', $withDashes);
        } else if (preg_match('/\bMaj\b/', $withDashes)) {
            $month = str_replace('Maj', '05', $withDashes);
        } else if (preg_match('/\bMarts\b/', $withDashes)) {
            $month = str_replace('Juni', '06', $withDashes);
        } else if (preg_match('/\bJuli\b/', $withDashes)) {
            $month = str_replace('Juli', '07', $withDashes);
        } else if (preg_match('/\bAugust\b/', $withDashes)) {
            $month = str_replace('August', '08', $withDashes);
        } else if (preg_match('/\bSeptember\b/', $withDashes)) {
            $month = str_replace('September', '09', $withDashes);
        } else if (preg_match('/\bOktober\b/', $withDashes)) {
            $month = str_replace('Oktober', '10', $withDashes);
        } else if (preg_match('/\bNovember\b/', $withDashes)) {
            $month = str_replace('November', '11', $withDashes);
        } else if (preg_match('/\bDecember\b/', $withDashes)) {
            $month = str_replace('December', '12', $withDashes);
        }
        
        return date("Y-m-d", strtotime($month));
    }

}
