<?php

class Posts
{

    public static function getAllPosts()
    {
        return dataManager::selectData(
            'SELECT * FROM posts',
            [],
            true
        );
    }

    public static function postID($post_id)
    {
        return dataManager::selectData(
            'SELECT * FROM posts WHERE id = :id',
            [
                'id' => $post_id
            ]
        );
    }

    public static function updatePost($id, $title, $content, $status)
    {
        $params = [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status
        ];

        return dataManager::updateData(
            'UPDATE posts SET title = :title, content = :content, status = :status WHERE id = :id',
            $params
        );
    }

    public static function addPost($title, $content, $user_id)
    {
        return dataManager::insertData(
            'INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)',
            [
                'title' => $title,
                'content' => $content,
                'user_id' => $user_id
            ]
        );
    }

    public static function deletePost($post_id)
    {
        return dataManager::deleteData(
            'DELETE FROM posts WHERE id = :id',
            [
                'id' => $post_id
            ]
        );
    }

    public static function getPublishedPost()
    {
        return dataManager::selectData(
            'SELECT * FROM posts where status = :status ORDER BY id DESC',
            [
                'status' => 'publish'
            ],
            true
        );
    }

    public static function getAllPostByRole($user_id)
    {
        if (roleManager::roleUser()) {
            return dataManager::selectData(
                'SELECT * FROM posts WHERE user_id = :id ORDER BY id DESC',
                [
                    'id' => $user_id
                ],
                true
            );
        } else {
            return dataManager::selectData(
                'SELECT * FROM posts ORDER BY id DESC',
                [],
                true
            );
        }
    }

    public static function getNameByID($post_id)
    {
        return dataManager::selectData(
            'SELECT 
            posts.id, users.name AS author_name
            FROM posts
            JOIN users
            ON users.id = posts.user_id
            WHERE posts.id = :post_id',
            [
                'post_id' => $post_id
            ],
        );
    }
}
