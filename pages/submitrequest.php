<?php

if (!roleManager::accessControl('user')) {
    header('Location: /login?user_must_login=must_login');
    exit;
}

CSRF::generateToken('submitrequest_form');

if($_SERVER['REQUEST_METHOD'] === "POST") {

    $error = formValidation::errorValidator(
        $_POST,
        [
            'name' => 'required',
            'email' => 'email_format',
            'message' => 'required',
        ]
    );

    $error .= formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'submitrequest_form_csrf_token'
        ]
    );

    if (!$error) {

        API::mailgunAPI($_POST['name'], $_POST['email'], $_POST['message']);

        CSRF::removeToken('submitrequest_form');
        header('Location: /others?submit_request=submit_success');
        exit;
    }
}

require "./parts/header.php" ?>

<div id="request">
    <?php require dirname(__DIR__) . "/parts/error.php" ?>
    <div class="container mt-5" style="max-width: 60rem;">
        <div class="card p-5">
            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                <h3 class="text-center">Submit a request</h3>
                <small class="text-muted d-flex justify-content-center align-items-center fst-italic">Need Help? Got good ideas? Need more topic? Submit a request!</small>
                <input type="text" name="name" class="form-control mt-3" placeholder="Name">
                <input type="text" name="email" class="form-control mt-3" placeholder="Email">
                <textarea name="message" id="" cols="30" rows="5" class="mt-3 p-3 w-100" placeholder="Message"></textarea>
                <div class="d-flex justify-content-end align-items-end gap-1">
                    <a href="/others" class="btn btn-sm btn-danger">Back</a>
                    <button class="btn btn-sm btn-success mt-3">Submit</button>
                </div>
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('submitrequest_form') ?>">
            </form>
        </div>
    </div>

</div>

<?php require "./parts/footer.php" ?>