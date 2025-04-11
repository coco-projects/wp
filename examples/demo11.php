<?php

    require 'common.php';

    $res = $manager->getTagsIds([
        'tag111',
        'tag222',
    ]);
   
   print_r($res);exit;;
