<?php

if (!function_exists('env')) {
    function env($key, $default=null) {
        return isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }
}

if (!function_exists('dir2namespace')) {
    function dir2namespace($dir) {
        return str_replace('/', '\\', $dir);
    }
}
