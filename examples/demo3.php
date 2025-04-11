<?php

    require 'common.php';

    $replace = [
        "dev6081"        => "dev6084",
        "http://dev6081" => "http://dev6084",
        "/var/www/6081/" => "/var/www/6084/",
        "大白熊"         => "牛牛",
    ];

    $manager->replaceAll($replace);
