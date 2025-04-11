<?php

    require 'common.php';

    $img = realpath('../data/1.jpg');

    $res = $manager->addMedia($img, '../data1', 'http://dev6080');


