<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/19/2017
 * Time: 11:14 PM
 */

namespace App;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Utility
{
    /**
     * @return Session
     */
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Get Symfony to interface with this existing session
        $session = new Session(new PhpBridgeSessionStorage());

        // symfony will now interface with the existing PHP session
        $session->start();

        return $session;
    }
}