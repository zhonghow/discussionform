<?php

if (!roleManager::accessControl('admin')) {
    if (sessionManager::isLoggedIn()) {
        header('Location: /dashboard?user_no_permission=no_permission');
        exit;
    } else {
        header('Location: /login?user_no_permission=no_permission');
        exit;
    }
}

CSRF::generateToken('add_user_form');

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $rules = [
        'name' => 'required',
        'email' => 'email_format',
        'password' => 'password_length',
        'confirm_password' => 'password_match',
        'role' => 'required',
    ];

    $error = formValidation::errorValidator(
        $_POST,
        $rules
    );

    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'add_user_form_csrf_token'
        ]
    );

    if (formValidation::emailUnique($_POST['email'])) {
        $error .= formValidation::emailUnique($_POST['email']);
    }

    if (!$error) {

        User::addUser(
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['role']
        );

        CSRF::removeToken('add_user_form');

        header(
            'Location: /manage-user?added_user=add_user_true'
                . '&name=' . $_POST['name'] .
                '&email=' . $_POST['email'] .
                '&role=' . $_POST['role']
        );
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";

?>

<div id="addUser">
    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <div class="container mt-5" style="max-width: 60rem;">

        <div class="form-container container rounded w-100 p-0 d-flex flex-column justify-content-center align-items-center">
            <div class="text-center mb-1">
                <h1 class="hr-line">Add User</h1>
            </div>

            <div class="card" style="width: 35rem; height: 13rem">
                <div class="row g-0 h-100 d-flex justify-content-center align-items-center">

                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <div class="profile d-flex justify-content-center align-items-start">
                            <i class="bi bi-person-fill d-flex"></i>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div class="text-start">
                                <h5 id="editCardName" class="card-subtitle m-0">Enter Name</h5>
                                <p id="editCardEmail" class="card-text my-1">Enter Email</p>
                                <span id="cardSelect" class="badge rounded-pill text-bg-warning">Select Role</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                <div class="input-container text-start d-flex justify-content-center mt-3">
                    <div>
                        <input id="editInputNames" name="name" type="text" class="form-control" maxlength="16" placeholder="Name">
                    </div>
                    <div>
                        <input id="editInputEmail" name="email" type="email" class="form-control" maxlength="64" placeholder="Email">
                    </div>
                </div>

                <div class="input-container text-start d-flex justify-content-center mt-3">
                    <div>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center mt-3">
                    <select id="editSelect" class="form-select" name="role" id="role">
                        <option value="" disabled selected>Select an option</option>
                        <option value="user">User</option>
                        <option value="moderator">Moderator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                    <button type="submit" class="w-50 btn btn-success">Save</button>
                    <a href="/manage-user" class="w-50 btn btn-danger">Back</a>
                </div>
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('add_user_form') ?>">
            </form>

        </div>

    </div>

</div>

</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>