<?php

class formValidation
{
    public static function emailUnique($email)
    {
        $user = dataManager::selectData(
            'SELECT * FROM users WHERE email = :email',
            [
                'email' => $email
            ],
        );

        if ($user) {
            return "Email already exist.";
        }

        return false;
    }

    public static function errorValidator($data, $rules = [])
    {
        $error = false;
        foreach ($rules as $key => $condition) {
            switch ($condition) {
                case 'must_login':
                    if (!empty($data[$key])) $error .= "You must be logged in to do that.";
                    break;
                case 'already_login':
                    if (!empty($data[$key])) $error .= "You are already logged in.";
                    break;
                case 'no_permission':
                    if (!empty($data[$key])) $error .= "You have no permission to do that.";
                    break;
                case 'required':
                    if (empty($data[$key])) $error .= ucwords($key) . " field is required. <br /> ";
                    break;
                case 'password_length':
                    if (empty($data[$key])) {
                        $error .= ucwords($key) . " field is required. <br /> ";
                    } else if (strlen($data[$key]) < 8) {
                        $error .= ucwords($key) . " must be more than 8 characters. <br /> ";
                    }
                    break;
                case 'password_match':
                    if ($data['password'] !== $data['confirm_password']) {
                        $error .= "Password & Confirmation Password does not match. <br /> ";
                    }
                    break;
                case 'email_format':
                    if (empty($data[$key])) $error .= ucwords($key) . " field is required. <br /> ";
                    else if (!filter_var($data[$key], FILTER_VALIDATE_EMAIL)) $error .= ucwords($key) . " format is incorrect. <br /> ";
                    break;
            }
        }
        return $error;
    }

    public static function csrfValidator($data, $rules = [])
    {
        $error = false;
        foreach ($rules as $key => $condition) {
            switch ($condition) {
                case 'login_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'login_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'signup_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'signup_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'add_user_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'add_user_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'edit_user_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'edit_user_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'delete_user_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'delete_user_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'add_post_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'add_post_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'edit_post_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'edit_post_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'delete_post_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'delete_post_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'add_comment_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'add_comment_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'delete_comment_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'delete_comment_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
                case 'submitrequest_form_csrf_token':
                    if (!CSRF::tokenVerify($data[$key], 'submitrequest_form')) $error .= "Invalid CSRF Token. <br /> ";
                    break;
            }
        }
        return $error;
    }

    public static function successValidator($data, $rules = [])
    {
        $success = false;
        foreach ($rules as $key => $condition) {
            switch ($condition) {
                case 'login_true':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Welcome back, ' . $_SESSION['user']['name'],
                            'message' =>   'You have successfully logged in.'
                        ];
                    }
                    break;
                case 'register_true':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Welcome, ' . $_SESSION['user']['name'],
                            'message' =>   'You have successfully created an account.'
                        ];
                    }
                    break;
                case 'edit-user-true':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Action executed',
                            'message' => (isset($_GET['oldname']) || isset($_GET['oldemail']) || isset($_GET['oldrole']) ? 'User info updated in database. <br/><br /><u>Changes</u>' : 'No action were executed')
                                . (isset($_GET['oldname']) && isset($_GET['newname']) ? '<br/>' . $_GET['oldname'] . ' → ' . $_GET['newname'] : '')
                                . (isset($_GET['oldemail']) && isset($_GET['newemail']) ? '<br/>' . $_GET['oldemail'] . ' → ' . $_GET['newemail'] : '')
                                . (isset($_GET['oldrole']) && isset($_GET['newrole']) ? '<br/>' . ucwords($_GET['oldrole']) . ' → ' . ucwords($_GET['newrole']) : '')
                        ];
                    }
                    break;
                case 'add_user_true':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Action executed',
                            'message' => (isset($_GET['name']) || isset($_GET['email']) || isset($_GET['role']) ? 'User added to database. <br/><br/> <u>Info</u>' : 'No action were executed.')
                                . (isset($_GET['name']) ? '<br/>Name → ' . $_GET['name'] : '')
                                . (isset($_GET['email']) ? '<br/>Email → ' . $_GET['email'] : '')
                                . (isset($_GET['role']) ? '<br/>Role → ' . ucwords($_GET['role']) : '')
                        ];
                    }
                    break;
                case 'add-post-success':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Post added',
                            'message' => (isset($_GET['add-post']) ? 'You have successfully added a post with the following title \'' . $_GET['title'] . '\'' : '')
                                . '<br/><br/>Your post is now under "pending" status, it will be published once an moderator has reviewed it.'
                        ];
                    }
                    break;
                case 'delete_post_success':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Post deleted',
                            'message' => 'You have successfully deleted a post.'
                        ];
                    }
                    break;
                case 'success_logout':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Bye bye!',
                            'message' => 'You have successfully logged out.'
                        ];
                    }
                    break;
                case 'modified_true':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Post Modification',
                            'message' => 'You have successfully modified the post'
                        ];
                    }
                    break;
                case 'password_changed':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Action executed',
                            'message' => 'You have changed the password for ' . $_GET['user']
                        ];
                    }
                    break;
                case 'user_deleted_true':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'User deleted',
                            'message' => 'You have successfully deleted a user'
                        ];
                    }
                    break;
                case 'comment_add_success':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Comment added.',
                            'message' => 'You have commented on this post.'
                        ];
                    }
                    break;
                case 'comment_delete_success':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Comment deleted.',
                            'message' => 'You have deleted a comment on this post.'
                        ];
                    }
                    break;
                case 'submit_success':
                    if (isset($data[$key]) && !empty($data[$key])) {
                        $success = [
                            'title' => 'Request sent.',
                            'message' => 'Your support request has been sent.'
                        ];
                    }
                    break;
            }
        }
        return $success;
    }
}
