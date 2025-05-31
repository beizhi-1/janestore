<?php
// auth.php - simple session based authentication

session_start();
require_once __DIR__ . '/db.php';

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login() {
    if (!current_user()) {
        header('Location: /login.php');
        exit;
    }
}

function require_admin() {
    require_login();
    if (!current_user()['is_admin']) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}
?>
