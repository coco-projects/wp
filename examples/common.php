<?php

    use Coco\wp\Manager;

    require '../vendor/autoload.php';


    $manager = new Manager('redis_namespace');

    $manager->setRedisConfig(db: 14);
//    $manager->setMysqlConfig('wordpress_gamespace');
//    $manager->setMysqlConfig('wordpress_game_en');
    $manager->setMysqlConfig('wp_te_10100');

    $manager->setEnableEchoLog(true);
    $manager->setEnableRedisLog(true);

    $manager->initServer();
    $manager->initTableStruct();

