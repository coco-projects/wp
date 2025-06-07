<?php

    require 'common.php';

    $begin = '2021-2-5';
    $end   = date('Y-m-d');
    $times = 800;

    $manager->updateAllPostPublishTime($begin, $end, $times,true);
    $manager->updateAllPostView();
