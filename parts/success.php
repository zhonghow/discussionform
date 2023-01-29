<?php if (isset($success) && !empty($success)) : ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast bg-info-subtle" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="8000">
            <div class="toast-header bg-info-subtle border border-0">
                <img src="../assets/img/icon.png" class="me-2">
                <strong class="me-auto"><?= $success['title'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?= $success['message'] ?>
            </div>
        </div>
    </div>
<?php endif ?>