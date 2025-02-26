<?php

    use Coco\wp\Manager;

    require '../vendor/autoload.php';

    $manager = new Manager('redis_namespace');

    $manager->setRedisConfig(db: 14);
    $manager->setMysqlConfig('faka_dabaixiongshop_com_test');

    $manager->setEnableEchoLog(true);
    $manager->setEnableRedisLog(true);

    $manager->initServer();
    $manager->initTableStruct();

