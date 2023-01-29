<?php

class Comments
{
    public static function addComment($comment, $post_id, $user_id)
    {
        return dataManager::insertData(
            'INSERT INTO comments (comment, post_id, user_id) VALUES (:comment, :post_id, :user_id)',
            [
                'comment' => $comment,
                'post_id' => $post_id,
                'user_id' => $user_id
            ]
        );
    }

    public static function deleteComment($comment_id)
    {
        return dataManager::deleteData(
            'DELETE FROM comments WHERE id = :id',
            [
                'id' => $comment_id
            ]
        );
    }

    public static function getAllComments($post_id)
    {
        return dataManager::selectData(
            'SELECT 
            comments.user_id, users.name, comments.comment, comments.id
            FROM comments
            JOIN users
            JOIN posts
            ON comments.user_id = users.id
            WHERE comments.post_id = :id
            GROUP BY comments.id',
            [
                'id' => $post_id
            ],
            true
        );
    }

    public static function getCommentID($comment_id)
    {
        return dataManager::selectData(
            'SELECT * FROM comments WHERE id = :id',
            [
                'id' => $comment_id
            ]
        );
    }
}
