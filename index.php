<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/15/2017
 * Time: 4:28 AM
 */

require __DIR__ . "/vendor/autoload.php";

if (\App\Config::from('app')->get('dev')) {
    \Symfony\Component\Debug\Debug::enable();
} else {
    echo !\App\Config::from('app')->get('dev');
}

$dbConfig = new \App\Config('db');
\DbModel\Model::$database = $dbConfig->get('database');
\DbModel\Model::$host = $dbConfig->get('host');
\DbModel\Model::$username = $dbConfig->get('user');
\DbModel\Model::$password = $dbConfig->get('password');

if (!strcasecmp($_SERVER['REQUEST_URI'], '/')) {
    (new \Symfony\Component\HttpFoundation\RedirectResponse('/views/auth/login.php'))->send();
}