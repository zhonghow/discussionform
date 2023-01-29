<?php

if (!sessionManager::isLoggedIn()) {
    header('Location: /login?user_must_login=must_login');
    exit;
}

CSRF::generateToken('edit_post_form');

$post = Posts::postID($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $error = formValidation::errorValidator(
        $_POST,
        [
            'title' => 'required',
            'content' => 'required',
        ]
    );

    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'edit_post_form_csrf_token'
        ]
    );

    if (!$error) {

        if (roleManager::roleUser()) {
            Posts::updatePost(
                $post['id'],
                $_POST['title'],
                $_POST['content'],
                $_POST['status'] = 'pending',
            );
        } else {
            Posts::updatePost(
                $post['id'],
                $_POST['title'],
                $_POST['content'],
                $_POST['status']
            );
        }

        CSRF::removeToken('edit_post_form');

        header('Location: /manage-post?modified=modified_true');
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";


?>

<div id="editPost">
    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <div class="d-flex justify-content-center mt-5">
        <h1 class="hr-line">Edit Post</h1>
    </div>

    <div class="container my-5 bg-light p-5" style="max-width: 50rem;">

        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <div class="px-3 d-flex flex-column justify-content-end ">
                <label for="postTitle" class="px-1">Title</label>
                <input type="text" name="title" id="postTitle" maxlength="64" value="<?= $post['title'] ?>" contenteditable="true" class="form-control" placeholder="Enter your post's title...">
            </div>

            <div class="px-3 d-flex flex-column justify-content-end mt-3">
                <label for="postContent" class="px-1">Content</label>
                <textarea class="form-control p-3" name="content" id="postContent" rows="10" style="max-height: fit-content;" placeholder="Enter your post's content..."><?= $post['content'] ?></textarea>
            </div>

            <?php if (!roleManager::roleUser()) : ?>
                <div class="px-3 d-flex flex-column mt-3">
                    <label for="role" class="form-label m-0">Select Status</label>
                    <select name="status" id="role" class="form-control">
                        <option value="pending" <?= ($post['status'] === 'pending' ? 'selected' : '') ?>>Pending For Review</option>
                        <option value="publish" <?= ($post['status'] === 'publish' ? 'selected' : '') ?>>Publish</option>
                    </select>
                </div>
            <?php endif ?>

            <div class="d-flex justify-content-end px-3 gap-1 mt-3">
                <button type="submit" class="btn btn-success btn-sm">Update</button>
                <a class="btn btn-danger btn-sm" href="/manage-post">Cancel</a>
            </div>
            <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('edit_post_form') ?>">
        </form>

    </div>
</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>