<?php
// includes/check_login.php
session_start();

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: /jobwave/login/login.php");
        exit(); // very important so the rest of the page does NOT run
    }
}
?>
