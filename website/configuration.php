<?php

    define('DEVELOPMENT', true);

    define('WEBSITE_TITLE', 'Exchange Online');

    define('BASE_URL', 'http://exchange.dev');

    define('SALT', 'c5%edGe0f$3b(d');   // Salt for tokens and passwords.


    if (DEVELOPMENT) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }


    spl_autoload_register(function ($class_name) {
        if (file_exists(__DIR__ . '/' . str_replace('\\', '/', $class_name) . '.php')) {
            include __DIR__ . '/' . str_replace('\\', '/', $class_name) . '.php';

        } else if (file_exists(__DIR__ . '/models/' . str_replace('\\', '/', $class_name) . '.php')) {
            include __DIR__ . '/models/' . str_replace('\\', '/', $class_name) . '.php';
        }
    });
