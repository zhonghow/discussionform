<?php

if (!sessionManager::isLoggedIn()) {
    header('Location: /login?user_must_login=must_login');
    exit;
}

require dirname(__DIR__) . "/parts/header.php"; ?>

<div id="CatFacts" style="height: 100vh;">

    <div class="container d-flex flex-column justify-content-center align-items-center" style="max-width: 60rem; height: 100%;">



        <div class="item-container h-100 mt-5 d-flex flex-column justify-content-center align-items-center">
            <div class="text-center mb-3">
                <h1 class="hr-line">Cat Facts</h1>
            </div>

            <div class="card mb-3" style="width: 500px;">
                <div class="row g-0">
                    <div class="d-flex flex-column">
                        <div class="card-body">
                            <h5 class="card-title"><u>Cat Facts</u></h5>
                            <p class="card-text"><?= API::catFact()->fact ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex w-100 justify-content-end gap-1">
                <div>
                    <a href="/others" class="btn btn-sm btn-danger">Back</a>
                    <a href="/cat-facts" class="btn btn-sm btn-success">New Facts</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require dirname(__DIR__) . "/parts/footer.php"; ?>