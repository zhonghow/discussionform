<?php

if (!roleManager::accessControl('user')) {
    header('Location: /login?user_must_login=must_login');
    exit;
}

CSRF::generateToken('add_post_form');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $rules = [
        'title' => 'required',
        'content' => 'required',
    ];

    $error = formValidation::errorValidator(
        $_POST,
        $rules
    );

    $error = formValidation::csrfValidator(
        $_POST,
        [
            'csrf_token' => 'add_post_form_csrf_token'
        ]
    );

    if (!$error) {

        Posts::addPost(
            $_POST['title'],
            trim($_POST['content']),
            $_SESSION['user']['id']
        );

        CSRF::removeToken('add_post_form');
        header('Location: /manage-post?add-post=add-post-success&title=' . $_POST['title']);
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";
?>

<div id="addPost">
    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <div class="d-flex justify-content-center mt-5">
        <h1 class="hr-line">New Post</h1>
    </div>

    <div class="container mt-5 bg-light p-5" style="max-width: 50rem;">
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <div class="px-3 d-flex flex-column justify-content-end ">
                <label for="postTitle" class="text-center mb-1">Title</label>
                <input type="text" name="title" id="postTitle" maxlength="64" class="form-control" placeholder="Enter your post's title...">
            </div>

            <div class="px-3 d-flex flex-column justify-content-end mt-3">
                <label for="postContent" class="text-center mb-1">Content</label>
                <textarea class="form-control p-3" name="content" id="postContent" cols="30" rows="4" placeholder="Enter your post's content..."></textarea>
            </div>

            <div class="d-flex justify-content-end px-3 gap-1 mt-3">
                <button class="btn btn-success btn-sm">Add</button>
                <a href="/manage-post" class="btn btn-danger btn-sm">Cancel</a>
            </div>
            <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('add_post_form') ?>">
        </form>

    </div>
</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>