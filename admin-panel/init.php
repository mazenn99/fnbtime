<?php
    require_once 'dbConnection.php';
    $func = 'includes/function/'; #this dir for function
    $tpl = 'includes/template/'; # This to call header and footer and navbar
    $sendEm = 'includes/classes/';
    include_once 'config.php';
    require_once $func . 'function.php';
    if (!isset($header)) {
        include_once $tpl . 'header.php';
    } # this mean if you don't want to include header in page use $header empty varible


    if (!isset($navbar)) {
        include_once $tpl . 'navbar.php';
    } # this mean if you don't want to include header in page use $header empty varible


