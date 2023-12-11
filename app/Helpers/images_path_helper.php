<?php

// if (!defined('BASEPATH')) {
//     exit('No direct script access allowed');
// }

if (!function_exists('get_images_path')) {
    function get_images_path()
    {
        if (isset($_SERVER['HTTPS']) &&
                  ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
                  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return 'https://' . $_SERVER['HTTP_HOST'] . '/assets/certificates/';
        } else {
            return 'http://' . $_SERVER['HTTP_HOST'] . '/assets/certificates/';
        }
    }
}
