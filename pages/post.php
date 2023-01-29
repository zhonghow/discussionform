<?php

$post = Posts::postID($_GET['id']);
$author = Posts::getNameByID($post['id']);

CSRF::generateToken('add_comment_form');
CSRF::generateToken('delete_comment_form');

$success = formValidation::successValidator(
    $_GET,
    [
        'comment_success' => 'comment_add_success',
        'comment_delete' => 'comment_delete_success'
    ]
);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'addComment':
                if (!sessionManager::isLoggedIn()) {
                    header('Location: /login?user_must_login=must_login');
                    exit;
                } else {
                    $error = formValidation::errorValidator(
                        $_POST,
                        [
                            'comment' => 'required'
                        ]
                    );

                    $error = formValidation::csrfValidator(
                        $_POST,
                        [
                            'csrf_token' => 'add_comment_form_csrf_token'
                        ]
                    );

                    if (!$error) {
                        Comments::addComment(
                            trim($_POST['comment']),
                            $_GET['id'],
                            $_SESSION['user']['id']
                        );

                        CSRF::removeToken('add_comment_form');

                        header('Location: /post?id=' . $post['id'] . '&comment_success=comment_add_success');
                        exit;
                    }
                }

                break;

            case 'deleteComment':

                $rules = [
                    'csrf_token' => 'delete_comment_form_csrf_token'
                ];

                $error = formValidation::csrfValidator(
                    $_POST,
                    $rules
                );

                if (!$error) {
                    Comments::deleteComment(
                        $_POST['id']
                    );
                    CSRF::removeToken('delete_comment_form');

                    header('Location: /post?id=' . $post['id'] . '&comment_delete=comment_delete_success');
                    exit;
                }
                break;
        }
    }
}



require dirname(__DIR__) . "/parts/header.php";
?>

<div id="postPage">

    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <?php require dirname(__DIR__) . "/parts/success.php"; ?>
    <div class="container my-5" style="max-width: 60rem;">
        <div class="card p-5">
            <div class="card-tile text-center">
                <h1><?= $post['title'] ?></h1>
            </div>
            <hr>
            <div class="card-body text-start">
                <p class="lead"> <?= nl2br($post['content']) ?></p>
            </div>
            <div class="card-footer d-flex bg-white text-secondary p-0 pt-3">
                <div>
                    <a href="<?= (sessionManager::isLoggedIn() ? '/manage-post' : '/') ?>" class="btn btn-sm btn-secondary">Back</a>

                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#comment-<?= $post['id'] ?>">
                        Leave a comment
                    </button>

                    <div class="modal fade" id="comment-<?= $post['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Leave a comment</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                    <input type="hidden" name="action" value="addComment">
                                    <div class="modal-body">
                                        <div class="d-flex flex-column">
                                            <label for="comment" class="px-3">Comment</label>
                                            <textarea name="comment" id="comment" cols="30" rows="10" class="p-3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success btn-sm">Submit Comment</button>
                                    </div>
                                    <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('add_comment_form') ?>">
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="ms-auto text-end fst-italic">
                    Posted By: <?= $author['author_name'] ?>
                </div>
            </div>
        </div>

        <?php foreach (Comments::getAllComments($post['id']) as $comment) : ?>
            <div class="card mt-2 p-3 position-relative">
                <div class="card-title m-0 px-3">
                    <div class="d-flex justify-content-between">
                        <h5 class="m-0 d-flex justify-content-center align-items-center"><?= $comment['name'] ?> commented:</h5>
                        <?php if (roleManager::accessControl('moderator')) : ?>
                            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                <input type="hidden" name="action" value="deleteComment">
                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('delete_comment_form') ?>">
                                <button type="submit" name="delete" class="btn btn-sm btn-danger"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                        <?php endif ?>
                    </div>
                </div>
                <div class="card-body p-0 px-3">
                    <p class="m-0">"<?= $comment['comment'] ?>"</p>
                </div>
            </div>
        <?php endforeach ?>
    </div>

</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>