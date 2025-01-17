<?php

    use Coco\wp\Manager;

    require '../vendor/autoload.php';

    $manager = new Manager();
    $manager->initMysql(db: 'wordpress_te_page');
    $manager->initTableStruct();
    $manager->enableEchoHandler();
    $manager->enableRedisHandler(db: 14);

