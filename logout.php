<?php
    session_start();
    $getTitle = 'Logout';
    include_once 'init.php';
if (isset($_SESSION['username'])) {
    $_SESSION = [];
    $_SESSION["success_message"] = "You are logged Out See you soon";
    header('location:index.php');
    die();
}