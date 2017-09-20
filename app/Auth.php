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

        $userField = Config::from('auth')->get('username');

        $count = Model::count('users', "{$userField} = '$username' AND password = '$password'");

        // If matches log in
        if ($count) {
            $session->set('username', $username);
            (new RedirectResponse("/views/SendSms.php"))->send();
        }
        return false;
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
     * Registers a new user
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public static function register($request)
    {
        $pass = $request->get('password');
        $confirmPass = $request->get('conf-password');
        $email = $request->get('email');
        $errors = [];

        if (strcmp($pass, $confirmPass)) {
            $errors[] = "password-mismatch";
        }

        if (Model::count('users', "email = '$email'")) {
            $errors[] = "email exists";
        }

        if (count($errors)) {
            return $errors;
        }

        (new Model())
            ->set('name', $request->get('name'))
            ->set('email', $email)
            ->set('password', $pass)
            ->save('users');


        (new RedirectResponse("/views/auth/login.php"))->send();
    }
}