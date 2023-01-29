<?php

class User
{
    /* -------------------------------------------------------------------------- */
    /*                      Retrieve all the user in database                     */
    /* -------------------------------------------------------------------------- */
    /*   We dont use WHERE here because we are getting everything from database   */
    /* -------------------------------------------------------------------------- */

    public static function getAllUsers()
    {
        return dataManager::selectData(
            'SELECT * FROM users ORDER BY role ASC',
            [],
            true
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                       Retrieve user ID from database                       */
    /* -------------------------------------------------------------------------- */

    public static function userID($user_id)
    {
        return dataManager::selectData(
            'SELECT * FROM users WHERE id = :id',
            [
                'id' => $user_id
            ]
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                         Update Details To Database                         */
    /* -------------------------------------------------------------------------- */
    /* -------------------------------------------------------------------------- */
    /*                                Setup Params                                */
    /*       Check for password, if password is available then add to params      */
    /*                       Update user data into database                       */
    /* -------------------------------------------------------------------------- */

    public static function updateUser($id, $name, $email, $role, $password = null)
    {
        $params = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];

        if ($password) {
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        return dataManager::updateData(
            'UPDATE users SET name = :name, email = :email,' . ($password ? 'password = :password,' : '')
                . 'role = :role WHERE id = :id',
            $params
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                            Add user to database                            */
    /* -------------------------------------------------------------------------- */

    public static function addUser($name, $email, $password, $role)
    {
        return dataManager::insertData(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)',
            [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ]
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                          Delete user from database                         */
    /* -------------------------------------------------------------------------- */

    public static function deleteUser($id)
    {
        return dataManager::deleteData(
            'DELETE FROM users WHERE id = :id',
            [
                'id' => $id
            ]
        );
    }
}
