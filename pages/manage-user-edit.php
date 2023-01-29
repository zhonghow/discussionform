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

CSRF::generateToken('edit_user_form');

$user = User::userID($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $password_change = (isset($_POST['password']) && !empty($_POST['password']) ||
        isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) ? true : false;

    $rules = [
        'name' => 'required',
        'email' => 'email_format',
        'role' => 'required',
    ];

    if ($password_change) {
        $rules['password'] = 'password_length';
        $rules['confirm_password'] = 'password_match';
    }

    $error = formValidation::errorValidator(
        $_POST,
        $rules
    );
    
    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'edit_user_form_csrf_token'
        ]
    );

    if ($user['email'] !== $_POST['email']) {
        $error .= formValidation::emailUnique($_POST['email']);
    }

    if (!$error) {

        User::updateUser(
            $user['id'],
            $_POST['name'],
            $_POST['email'],
            $_POST['role'],
            ($password_change ? $_POST['password'] : null)
        );

        CSRF::removeToken('edit_user_form');

        /* -------------------------------------------------------------------------- */
        /*      Redirect user to the manage-user page with the following queries:     */
        /* -------------------------------------------------------------------------- */
        /*                1. edited_user=edit-user-true => Create Toast               */
        /*   2. If there is a name change: oldname, newname => Get old and new name   */
        /*         3. If email change, oldemail, newemail => Get old/new email        */
        /*           4. If role change, oldrole, newrole => Get old/new role          */
        /* -------------------------------------------------------------------------- */

        header(
            'Location: /manage-user?edited_user=edit-user-true'
                . ($user['name'] !== $_POST['name'] ? '&oldname=' . $user['name'] . "&newname=" . $_POST['name'] : '')
                . ($user['email'] !== $_POST['email'] ? '&oldemail=' . $user['email'] . '&newemail=' . $_POST['email'] : '')
                . ($user['role'] !== $_POST['role'] ? '&oldrole=' . $user['role'] . '&newrole=' . $_POST['role'] : '')
                . ($password_change ? '&passwordchange=password_changed&user=' . $_POST['name'] : '')
        );
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";

?>

<div id="editUser">

    <div class="container mt-5" style="max-width: 60rem;">

        <div class="form-container container rounded w-100 p-0 d-flex flex-column justify-content-center align-items-center">
            <div class="text-center mb-1">
                <h1 class="hr-line">Edit User</h1>
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
                                <h5 id="editCardName" class="card-subtitle m-0"><?= $user['name'] ?></h5>
                                <p id="editCardEmail" class="card-text my-1"><?= $user['email'] ?></p>
                                <?php if ($user['role'] == 'user') : ?>
                                    <span id="cardSelect" class="badge rounded-pill text-bg-success"><?= ucwords($user['role']) ?></span>
                                <?php elseif ($user['role'] == 'moderator') : ?>
                                    <span id="cardSelect" class="badge rounded-pill text-bg-primary"><?= ucwords($user['role']) ?></span>
                                <?php elseif ($user['role'] == 'admin') : ?>
                                    <span id="cardSelect" class="badge rounded-pill text-bg-danger"><?= ucwords($user['role']) ?></span>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                <div class="input-container text-start d-flex justify-content-center mt-3">
                    <div>
                        <input id="editInputNames" name="name" type="text" class="form-control" value="<?= $user['name'] ?>" maxlength="16" placeholder="Name">
                    </div>
                    <div>
                        <input id="editInputEmail" name="email" type="email" class="form-control" value="<?= $user['email'] ?>" maxlength="64" placeholder="Email">
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
                        <option value="user" <?= ($user['role'] == 'user' ? 'selected' : '') ?>>User</option>
                        <option value="moderator" <?= ($user['role'] == 'moderator' ? 'selected' : '') ?>>Moderator</option>
                        <option value="admin" <?= ($user['role'] == 'admin' ? 'selected' : '') ?>>Admin</option>
                    </select>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                    <button type="submit" class="w-50 btn btn-success">Save</button>
                    <a href="/manage-user" class="w-50 btn btn-danger">Back</a>
                </div>
                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('edit_user_form') ?>">
            </form>

        </div>

    </div>

</div>

</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>