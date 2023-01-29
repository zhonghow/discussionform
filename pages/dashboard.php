<?php

if (!sessionManager::isLoggedIn()) {
    header('Location: /login?user_must_login=must_login');
    exit;
}

$error = formValidation::errorValidator(
    $_GET,
    [
        'user_already_login' => 'already_login',
        'user_no_permission' => 'no_permission'
    ]
);

$success = formValidation::successValidator(
    $_GET,
    [
        'login_success' => 'login_true',
        'register_success' => 'register_true'
    ]
);


require dirname(__DIR__) . "/parts/header.php"; ?>

<div id="Dashboard">
    <?php require dirname(__DIR__) . "/parts/success.php" ?>
    <?php require dirname(__DIR__) . "/parts/error.php" ?>

    <div class="container d-flex flex-column justify-content-center align-items-center" style="max-width: 60rem; height: 100%;">
        <div class="text-center mb-3">
            <h1 class="hr-line">Dashboard</h1>
        </div>
        <div class="post-container d-flex justify-content-between align-items-center">
            <a href="/manage-post" class="box d-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-pencil-square"></i>
                <h5 class="m-0">Manage Post</h5>
            </a>
            <?php if (!roleManager::roleUser()) : ?>
                <a href="/manage-user" class="box d-flex flex-column justify-content-center align-items-center p-5">
                    <i class="bi bi-people-fill"></i>
                    <h5 class="m-0">Manage User</h5>
                </a>
            <?php endif ?>
            <a href="/others" class="box d-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-puzzle-fill"></i>
                <h5 class="m-0">Others</h5>
            </a>
            <a href="/" class="box d-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-house-fill"></i>
                <h5 class="m-0">Home</h5>
            </a>
        </div>
    </div>

</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>