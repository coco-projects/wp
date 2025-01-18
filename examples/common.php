<?php

    use Coco\wp\Manager;

    require '../vendor/autoload.php';

    $manager = new Manager('10100');

    $manager->setRedisConfig(db: 14);
    $manager->setMysqlConfig('wordpress_te_page');

    $manager->setEnableEchoLog(true);
    $manager->setEnableRedisLog(true);

    $manager->initServer();
    $manager->initTableStruct();

