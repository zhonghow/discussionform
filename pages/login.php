<?php


if (sessionManager::isLoggedIn()) {
    header('Location: /dashboard?user_already_login=already_login');
    exit;
}

$error = formValidation::errorValidator(
    $_GET,
    [
        'user_no_permission' => 'no_permission',
        'user_must_login' => 'must_login',
    ]
);


CSRF::generateToken('login_form');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = formValidation::errorValidator(
        $_POST,
        [
            'email' => 'email_format',
            'password' => 'password_length',
        ]
    );

    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'login_form_csrf_token'
        ]
    );

    if (!$error) {
        $user_id = userAuth::logIn($email, $password);

        if (!$user_id) {
            $error = 'Email or password is incorrect.';
        } else {

            sessionManager::setUserSession($user_id);

            CSRF::removeToken('login_form');

            header('Location: /dashboard?login_success=true');
            exit;
        }
    }
}

require dirname(__DIR__) . "/parts/header.php";

?>

<div id="loginPage">
    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="box p-3 py-5 d-flex flex-column justify-content-center align-items-center">
            <div class="profile d-flex justify-content-center align-items-center m-0">
                <i class="bi bi-person-fill d-flex"></i>
            </div>
            <div class="text-center mt-3 mb-3">
                <h4 class="m-0">Log In</h4>
                <p class="m-0">Welcome User!</p>
            </div>

            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" class="d-flex flex-column justify-content-center align-items-center w-100">
                <div>
                    <input type="email" class="form-control" placeholder="Email" title="Enter Email" name="email">
                </div>
                <div>
                    <input type="password" class="form-control mt-2" placeholder="Password" title="Enter Password" name="password">
                </div>
                <button class="btn btn-success mt-3 w-50">Submit</button>
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('login_form'); ?>">
            </form>

            <a href="/" class="btn btn-danger mt-1 w-50">Back</a>
            <div class="mt-3">
                <a href="/signup" class="text-decoration-none">Create an account</a>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>