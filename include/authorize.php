<?php

if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_id'])){

}
if(!isset($_COOKIE['user_id']) || !isset($_COOKIE['name'])){
    // Redirect to the home page
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . ' ';
    header('Location: ' . $home_url);
    exit();
}