<?php

class CSRF
{
    public static function generateToken($prefix = '')
    {
        if (!isset($_SESSION[$prefix . '_csrf_token'])) {
            $_SESSION[$prefix . '_csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function removeToken($prefix = '')
    {
        if (isset($_SESSION[$prefix . '_csrf_token'])) {
            unset($_SESSION[$prefix . '_csrf_token']);
        }
    }

    public static function tokenVerify($formToken, $prefix = '')
    {
        if (isset($_SESSION[$prefix . '_csrf_token']) && $_SESSION[$prefix . '_csrf_token'] === $formToken) {
            return true;
        }
        return false;
    }

    public static function getToken($prefix = '')
    {
        if (isset($_SESSION[$prefix . '_csrf_token'])) {
            return $_SESSION[$prefix . '_csrf_token'];
        }
        return false;
    }
}
