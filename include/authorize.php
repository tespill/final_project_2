<?php
session_start();

// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    } else {
        // Redirect to the home page
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . ' ';
        header('Location: ' . $home_url);
        exit();
    }
}
?>