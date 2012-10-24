<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('getError')) {
    function getError($str) {
        return '<div class="Error">'.$str.'</div>';
    }
}if ( ! function_exists('getWarning')) {
    function getWarning($str) {
        return '<div class="Warning">'.$str.'</div>';
    }
}if ( ! function_exists('getMessage')) {
    function getMessage($str) {
        return '<div class="Message">'.$str.'</div>';
    }
}
