<?php

class userAuth // Every User Authentication Related Function
{

    /* ---------------------------- Sign Up Function ---------------------------- */
    public static function signUp($name, $email, $password)
    {
        return dataManager::selectData(
            'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)',
            [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]
        );
    }
    /* -------------------------------------------------------------------------- */


    /* ----------------------------- Log In Function ---------------------------- */
    public static function logIn($email, $password)
    {
        $user_id = false;

        $user = dataManager::selectData(
            'SELECT * FROM users WHERE email = :email',
            [
                'email' => $email,
            ]
        );

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $user_id = $user['id'];
            }
        }

        return $user_id;
    }
    /* -------------------------------------------------------------------------- */
}

class sessionManager // Every session related function
{
    /* ---------------------------- Set user session ---------------------------- */
    public static function setUserSession($user_id)
    {
        $user = dataManager::selectData(
            'SELECT * FROM users WHERE id = :id',
            [
                'id' => $user_id
            ]
        );

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }
    /* -------------------------------------------------------------------------- */

    /* ------------------------- Check user login status ------------------------ */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }
    /* -------------------------------------------------------------------------- */

    /* ---------------------------- Log out function ---------------------------- */
    public static function logOut()
    {
        unset($_SESSION['user']);
    }
    /* -------------------------------------------------------------------------- */
}

class roleManager // Every Role Related Function
{
    /* -------------------- Get role of current session user -------------------- */
    public static function getRole()
    {
        if (sessionManager::isLoggedIn()) {
            return $_SESSION['user']['role'];
        }

        return false;
    }
    /* -------------------------------------------------------------------------- */

    /* --------------------- Check if role is equal to admin -------------------- */
    public static function roleAdmin()
    {
        return self::getRole() == 'admin';
    }
    /* -------------------------------------------------------------------------- */

    /* ------------------- Check if role is equal to moderator ------------------ */
    public static function roleModerator()
    {
        return self::getRole() == 'moderator';
    }
    /* -------------------------------------------------------------------------- */

    /* --------------------- Check if role is equal to user --------------------- */
    public static function roleUser()
    {
        return self::getRole() == 'user';
    }
    /* -------------------------------------------------------------------------- */

    /* ------------------- Control what every role can access ------------------- */
    public static function accessControl($role)
    {
        if (sessionManager::isLoggedIn()) {
            switch ($role) {
                case 'admin':
                    return self::roleAdmin();
                    break;
                case 'moderator':
                    return self::roleAdmin() || self::roleModerator();
                    break;
                case 'user':
                    return self::roleAdmin() || self::roleModerator() || self::roleUser();
                    break;
            }
        }
    }
    /* -------------------------------------------------------------------------- */
}
