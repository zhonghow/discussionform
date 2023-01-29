<?php

if (!sessionManager::isLoggedIn()) {
    header('Location: /login?user_must_login=must_login');
    exit;
}

$success = formValidation::successValidator(
    $_GET,
    [
        'submit_request' => 'submit_success'
    ]
);

require dirname(__DIR__) . "/parts/header.php"; ?>

<div id="Other">
    <?php require dirname(__DIR__) . "/parts/success.php"; ?>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="max-width: 60rem; height: 100%;">
        <div class="text-center mb-3">
            <h1 class="hr-line">Others</h1>
        </div>

        <div class="post-container d-flex justify-content-between align-items-center">

            <a href="/cat-facts" class="box d-flex flex-column justify-content-center align-items-center p-5">
                <img src="../assets/img/paw-solid.svg" class="img-fluid my-3" style="width: 50px;">
                <h5 class="m-0">Cat Facts</h5>
            </a>

            <a href="/submit-request" class="box d-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-envelope-exclamation-fill"></i>
                <h5 class="m-0 text-center">Submit a request</h5>
            </a>

            <a href="/" class="box d-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-house-fill"></i>
                <h5 class="m-0">Home</h5>
            </a>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>