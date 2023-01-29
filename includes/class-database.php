<?php

class Database
{
    public static function connectDatabase()
    {
        try {
            $database = new PDO(
                'mysql:host=devkinsta_db;dbname=discussionform',
                'root',
                'qQs06NBbdQOEMav6'
            );
        } catch (Exception) {
            die("Database Connection Failed");
        }

        return $database;
    }
}

class dataManager
{
    public static function selectData($sql, $data = [], $list = false)
    {
        $statement = Database::connectDatabase()->prepare($sql);
        $statement->execute($data);

        if ($list) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
    }

    public static function insertData($sql, $data = [])
    {
        $statement = Database::connectDatabase()->prepare($sql);
        $statement->execute($data);
        return Database::connectDatabase()->lastInsertId();
    }

    public static function updateData($sql, $data = [])
    {
        $statement = Database::connectDatabase()->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }

    public static function deleteData($sql, $data = [])
    {
        $statement = Database::connectDatabase()->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }
}
