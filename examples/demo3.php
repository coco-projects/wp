<?php

    require 'common.php';

    $replace = [
//        "dev6041"        => "dev6080",
//        "http://dev6041" => "http://dev6080",
//        "/var/www/6041/" => "/var/www/6080/",
        "大白熊"         => "牛牛",
    ];

    $manager->replaceAll($replace);
