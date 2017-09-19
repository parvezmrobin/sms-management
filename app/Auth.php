<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/19/2017
 * Time: 11:01 PM
 */

namespace App;


use DbModel\Model;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Auth
{
    /**
     * Try to login using username and password
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public static function login($session, $request)
    {
        static::logout($session, $request);
        static::redirect($session);


        $username = $request->get('username');
        $password = $request->get('password');

        if (!$username || !$password) {
            return true;
        }

        Model::$database = Config::get('db', 'database');
        $userField = Config::get('auth', 'username');

        $count = Model::count('users', "{$userField} = '$username' AND password = '$password'");

        // If matches log in
        if ($count) {
            $session->set('username', $username);
            (new RedirectResponse("/views/SendSms.php"))->send();
        }
        return false;
    }


    /**
     * Redirects to home if logged in
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public static function redirect($session)
    {
        // Redirect if logged in
        if ($session->has('username')) {
            (new RedirectResponse("/views/SendSms.php"))->send();
        }
    }

    /**
     * Try to logout if asked
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public static function logout($session, $request)
    {
        // Logout
        if ($request->query->has('logout')) {
            $session->remove('username');
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public static function register($request)
    {
        (new Model())->set('name', $request->get('name'))
            ->set('email', $request->get('email'))
            ->set('password', $request->get('password'))
            ->save('users');

        (new RedirectResponse("/views/auth/login.php"))->send();
    }
}