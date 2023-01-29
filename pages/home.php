<?php

$success = formValidation::successValidator(
    $_GET,
    [
        'logout' => 'success_logout'
    ]
);

require dirname(__DIR__) . "/parts/header.php";
?>

<div id="homePage">
    <?php require dirname(__DIR__) . "/parts/success.php"; ?>
    <div class="container mt-5" style="max-width: 50rem;">

        <div class="nav-container container rounded navigation mx-auto">
            <div class="w-100 py-1 px-2">
                <div class="logo d-flex justify-content-end align-items-center">
                    <!-- Need PHP -->

                    <?php if (sessionManager::isLoggedIn()) : ?>
                        <div class="logIn d-flex">
                            <a href="/dashboard" class="btn btn-sm btn-warning px-3">Dashboard</a>
                            <a href="/logout" id="logOut" class="btn btn-sm btn-danger px-3">Log Out</a>
                        </div>
                    <?php else : ?>
                        <div class="notLogIn d-flex">
                            <a href="/login" class="btn btn-sm btn-light px-3">Log In</a>
                            <a href="/signup" class="btn btn-sm btn-light px-3">Sign Up</a>
                        </div>
                    <?php endif ?>

                </div>
            </div>
        </div>

        <div class="form-container container rounded w-100 mt-3 p-0 pb-5">

            <!-- Need PHP -->
            <div class="d-flex justify-content-start gap-2 flex-wrap">
                <?php foreach (Posts::getPublishedPost() as $post) : ?>
                    <div class="card position-relative" style="width: 15.8rem; height: 20rem;">
                        <div class="card-header w-100 text-center" style="max-height: 15%;">
                            <?= $post['title'] ?>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between" style="max-height: 70%;">

                            <?php
                            if (strlen($post['content']) < 150) {
                                echo '<p class="m-1">' . substr($post['content'], 0, 50) . '</p>';
                            } else {
                                echo '<p class="m-1">' . substr(nl2br($post['content']), 0, 150) . '...</p>';
                            }
                            ?>
                            <?php $author = Posts::getNameByID($post['id']); ?>
                            <small class="fst-italic text-muted d-flex justify-content-end">Posted By: <?= $author['author_name'] ?></small>
                        </div>
                        <div class="p-4 position-absolute bottom-0 start-0 w-100">
                            <a href="/post?id=<?= $post['id'] ?>" class="w-100 btn btn-sm btn-primary" style="border-radius: 20px">Continue Reading</a>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

    </div>

</div>

</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>