<?php

if (sessionManager::isLoggedIn()) {
    sessionManager::logOut();
}

header('Location:/?logout=success_logout');
exit;
