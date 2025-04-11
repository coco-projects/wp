<?php

    require 'common.php';

    $res = $manager->addTags([
        'tag222',
        'tag333',
        'tag444',
    ]);
    print_r($res);