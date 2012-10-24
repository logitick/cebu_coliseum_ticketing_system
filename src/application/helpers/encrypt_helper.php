<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('sha512')) {
    function sha512($str) {
        return hash("sha512", $str);
    }
}

if ( ! function_exists('ci_encrypt')) {
    function ci_encrypt($str) {
        $enc = sha512($str);
        $salt = substr(0, strlen($str));
        
        return hash("sha512", $enc.":".$salt);
    }
}