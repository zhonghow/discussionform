<?php

session_start();

require "includes/class-database.php";
require "includes/class-user.php";
require "includes/class-authentication.php";
require "includes/class-form-validation.php";
require "includes/class-csrf.php";
require "includes/class-posts.php";
require "includes/class-comments.php";
require "includes/function-api.php";
require "config.php";

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

switch ($path) {

    case 'login':
        require "./pages/login.php";
        break;
    case 'logout':
        require "./pages/logout.php";
        break;
    case 'signup':
    case 'register':
        require "./pages/signup.php";
        break;
    case 'dashboard':
        require "./pages/dashboard.php";
        break;
    case 'manage-post':
        require "./pages/manage-post.php";
        break;
    case 'edit-post':
        require "./pages/manage-post-edit.php";
        break;
    case 'delete-post':
        require "./pages/manage-post-delete.php";
        break;
    case 'new-post':
        require "./pages/manage-post-add.php";
        break;
    case 'manage-user':
        require "./pages/manage-user.php";
        break;
    case 'add-user':
        require "./pages/manage-user-add.php";
        break;
    case 'edit-user':
        require "./pages/manage-user-edit.php";
        break;
    case 'post':
        require "./pages/post.php";
        break;
    case 'others':
        require "./pages/others.php";
        break;
    case 'jokes':
        require "./pages/jokes.php";
        break;
    case 'cat-facts':
        require "./pages/catfacts.php";
        break;
    case 'submit-request':
        require "./pages/submitrequest.php";
        break;
    default:
        require "./pages/home.php";
        break;
}
