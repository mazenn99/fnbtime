<?php

//    $connection = [
//        "host" => "localhost",
//        "user" => "fnbtimeAdmin",
//        "password" => "RCet&=Cm}27^xsyL",
//        "database" => "fnbtime"
//    ];

   $connection = [
       "host" => "localhost",
       "user" => "root",
       "password" => "",
       "database" => "fnbtime"
   ];

    $mysql = new mysqli($connection['host'] , $connection['user'] , $connection['password'] , $connection['database']);

    if($mysql->connect_error) {
        echo 'error to connect to database try another time';
        die();
        }

?>