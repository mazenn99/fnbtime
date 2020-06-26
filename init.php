<?php

    $db     = 'admin-panel/'; #This Dir For database connection
    $func   = 'admin-panel/includes/function/'; #this dir for function
    $sendEm = 'admin-panel/includes/classes/';
    $tpl    = 'includes/template/'; # This to call header and footer and navbar
    $config = 'admin-panel/'; #This Call config file From admin

    require_once $db     . 'dbConnection.php';
    include_once $config . 'config.php';
    require_once $func   . 'function.php';
    if(!isset($header)) {include_once $tpl    . 'header.php';} # this mean if you don't want to include header in page use $header empty varible





