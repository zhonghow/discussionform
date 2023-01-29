<?php

CSRF::generateToken('signup_form');

if (sessionManager::isLoggedIn()) {
    header('Location: /dashboard?user_already_login=already_login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $error = formValidation::errorValidator(
        $_POST,
        [
            'name' => 'required',
            'email' => 'email_format',
            'password' => 'password_length',
            'confirm_password' => 'password_match',
        ]
    );

    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'signup_form_csrf_token'
        ]
    );

    if (formValidation::emailUnique($email)) {
        $error = formValidation::emailUnique($email);
    }

    if (!$error) {
        $user_id = userAuth::signUp(
            $name,
            $email,
            $password
        );

        sessionManager::setUserSession($user_id);

        CSRF::removeToken('signup_form');

        header('Location: /dashboard?register_success=true');
        exit;
    }
}


require dirname(__DIR__) . "/parts/header.php";
?>

<div id="signupPage">

    <?php require dirname(__DIR__) . "/parts/error.php"; ?>

    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="box p-3 py-5 d-flex flex-column justify-content-center align-items-center">
            <div class="profile d-flex justify-content-center align-items-center m-0">
                <i class="bi bi-person-fill d-flex"></i>
            </div>
            <div class="text-center mt-3">
                <h4 class="m-0">Sign Up</h4>
                <p class="m-0">Welcome User!</p>
            </div>
            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" class="w-100 d-flex flex-column align-items-center justify-content-center">
                <div>
                    <input type="text" name="name" class="form-control mt-3" placeholder="Username" title="Enter Username">
                </div>
                <div>
                    <input type="email" name="email" class="form-control mt-1" placeholder="Email" title="Enter Email">
                </div>
                <div>
                    <input type="password" name="password" id="password" class="form-control mt-1" placeholder="Password" title="Enter Password">
                </div>
                <div>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control mt-1" placeholder="Confirm Password" title="Enter Confirmation Password">
                </div>
                <button class="btn btn-success mt-3 w-50">Submit</button>
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('signup_form') ?>">
            </form>

            <a href="/" class="btn btn-danger mt-1 w-50">Back</a>

            <div class="mt-1">
                <a href="/login" class="text-decoration-none">Log into your account</a>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>