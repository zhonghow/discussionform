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

CSRF::generateToken('delete_user_form');

$success = formValidation::successValidator(
    $_GET,
    [
        'edited_user' => 'edit-user-true',
        'added_user' => 'add_user_true',
        'passwordchange' => 'password_changed',
        'user_deleted' => 'user_deleted_true'
    ]
);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $error = formValidation::errorValidator(
        $_POST,
        [
            'id' => 'required',
        ]
    );

    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'delete_user_form_csrf_token'
        ]
    );

    if (!$error) {

        User::deleteUser($_POST['id']);

        CSRF::removeToken('delete_user_form');

        header('Location: /manage-user?user_deleted=user_deleted_true');
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";



?>

<div id="manageUser">
    <?php require dirname(__DIR__) . "/parts/success.php"; ?>
    <div class="container mt-5" style="max-width: 60rem;">

        <div class="nav-container container rounded navigation">
            <div class="w-100 py-1">
                <div class="logo d-flex justify-content-end align-items-center">
                    <a href="/" class="btn btn-sm btn-light px-3"></i>Home</a>
                    <a href="/dashboard" class="btn btn-sm btn-warning px-3">Dashboard</a>
                </div>
            </div>
        </div>

        <div class="user-container mt-2">
            <div class="card p-5 pt-4">
                <table class="table text-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Need PHP -->
                        <h1 class="mb-0">User List</h1>
                        <a href="/add-user" class="btn btn-sm btn-success px-3">Add User</a>
                    </div>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (User::getAllUsers() as $index => $user) : ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td><?= $user['name'] ?></td>
                                <?php if ($user['role'] === 'user') : ?>
                                    <td><span class="badge text-bg-success">User</span></td>
                                <?php elseif ($user['role']  === 'moderator') : ?>
                                    <td><span class="badge text-bg-primary">Moderator</span></td>
                                <?php elseif ($user['role']  === 'admin') : ?>
                                    <td><span class="badge text-bg-danger">Admin</span></td>
                                <?php endif ?>
                                <td class="d-flex justify-content-center gap-1">
                                    <?php if ($user['role'] !== 'admin') : ?>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#details-<?= $user['id'] ?>">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>

                                        <div class="modal fade" id="details-<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5 mb-0" id="exampleModalLabel">User Details</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body d-flex flex-column text-start">
                                                        <small>Name: <?= $user['name'] ?></small>
                                                        <small>Email: <?= $user['email'] ?></small>
                                                        <small>Role: <?= ucwords($user['role']) ?></small>
                                                        <small>Account created at: <?= $user['created_at'] ?></small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="/edit-user?id=<?= $user['id'] ?>" class="btn btn-sm btn-success "><i class="bi bi-pencil-square"></i> </a>

                                        <button type="button" class="btn btn-sm btn-danger " data-bs-toggle="modal" data-bs-target="#user-<?= $user['id'] ?>">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    <?php else : ?>
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#details-<?= $user['id'] ?>">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </div>

                                        <div class="modal fade" id="details-<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5 mb-0" id="exampleModalLabel">User Details</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body d-flex flex-column text-start">
                                                        <small>Name: <?= $user['name'] ?></small>
                                                        <small>Email: <?= $user['email'] ?></small>
                                                        <small>Role: <?= ucwords($user['role']) ?></small>
                                                        <small>Account created at: <?= $user['created_at'] ?></small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>

                                    <div class="modal fade" id="user-<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    You are about to delete <span class="fst-italic"><?= $user['name'] ?></span> from the database.<br /> <br />
                                                    <small class="text-danger fst-italic">Action cannot be undone after deletion, are you sure you want to delete this user?</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">Confirm</button>
                                                        <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('delete_user_form') ?>">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>


        </div>

    </div>

</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>