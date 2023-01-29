<?php if (isset($error) && !empty($error)) : ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast bg-danger-subtle" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
            <div class="toast-header bg-danger-subtle border border-0">
                <img src="../assets/img/icon.png" class="me-2">
                <strong class="me-auto">You can't do that</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?= $error ?>
            </div>
        </div>
    </div>
<?php endif ?>