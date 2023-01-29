<?php

if (!roleManager::accessControl('user')) {
    header('Location:/login?user_no_permission=no_permission');
    exit;
}

CSRF::generateToken('delete_post_form');

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
            'csrf_token' => 'delete_post_form_csrf_token'
        ]
    );

    if (!$error) {

        Posts::deletePost($_POST['id']);

        CSRF::removeToken('delete_post_form');

        header('Location: /manage-post?delete-post=delete_post_success');
        exit;
    }
}

$success = formValidation::successValidator(
    $_GET,
    [
        'add-post' => 'add-post-success',
        'delete-post' => 'delete_post_success',
        'modified' => 'modified_true'
    ]
);

require dirname(__DIR__) . "/parts/header.php";
?>

<div id="managePost">
    <?php require dirname(__DIR__) . "/parts/success.php"; ?>
    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <div class="container mt-5" style="max-width: 70rem;">

        <div class="nav-container container rounded navigation mx-auto">
            <div class="w-100 py-1 px-2">
                <div class="logo d-flex justify-content-end align-items-center">
                    <a class="btn btn-light btn-sm px-3" href="/">Home</a>
                    <a class="btn btn-warning btn-sm px-3" href="/dashboard">Dashboard</a>
                </div>
            </div>
        </div>

        <div class="post-container mt-2">

            <div class="card p-5">
                <table class="table text-center">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="mb-0">List of Posts</h1>
                        <a class="btn btn-success btn-sm px-3" href="/new-post">New Post</a>
                    </div>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (Posts::getAllPostByRole($_SESSION['user']['id']) as $index => $post) : ?>
                            <?php if ($_SESSION['user']['id'] == $post['user_id'] || roleManager::accessControl('moderator')) : ?>
                                <tr>
                                    <th scope="row"><?= $index + 1 ?></th>
                                    <td><?= $post['title'] ?></td>
                                    <?php if ($post['status'] === "pending") : ?>
                                        <td><span class="badge text-bg-warning"><?= ucwords($post['status']) ?></span></td>
                                    <?php elseif ($post['status'] === "publish") : ?>
                                        <td><span class="badge text-bg-success"><?= ucwords($post['status']) ?></span></td>
                                    <?php endif ?>
                                    <td class="d-flex justify-content-center gap-1">
                                        <a href="/post?id=<?= $post['id'] ?>" class="btn btn-sm btn-secondary <?= ($post['status'] === "pending" ? 'disabled' : '') ?>"><i class="bi bi-eye-fill"></i></a>
                                        <a href="/edit-post?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>

                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#post-<?= $post['id'] ?>">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                        <div class="modal fade" id="post-<?= $post['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Post</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        You are about to delete this post: <br /> <br />
                                                        <div class="text-start">
                                                            Post #<?= $index + 1 ?> <br />
                                                            <?php $author = Posts::getNameByID($post['id']) ?>
                                                            Posted By - <?= $author['author_name'] ?> <br />
                                                            Post Title - <?= $post['title'] ?> <br />
                                                            Post Content: <br /> <?php
                                                                                    if (strlen($post['content']) < 150) {
                                                                                        echo substr(nl2br($post['content']), 0, 150);
                                                                                    } else {
                                                                                        echo substr(nl2br($post['content']), 0, 150) . "...";
                                                                                    }
                                                                                    ?> <br /><br />

                                                        </div>
                                                        <small class="text-danger fst-italic">Action cannot be undone after deletion, are you sure you want to delete this post?</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm">Confirm</button>
                                                            <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('delete_post_form') ?>">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>

</div>


<?php require dirname(__DIR__) . "/parts/footer.php"; ?>