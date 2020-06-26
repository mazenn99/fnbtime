<?php
    session_start();
    $getTitle  = 'Logout';
    include_once 'init.php';
    if (isset($_SESSION['admin'])) {
        $_SESSION = [];
        header('location:index.php');
        die();
    }