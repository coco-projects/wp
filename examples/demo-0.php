<?php

    require 'common.php';

    $matomoSiteId = 7;
    $matomoUrl    = "http://dev6058/";
    $matomoToken  = "28b96e44af9997f60808c4cafe652ace";

    $wpUrl = "http://dev6080/";

    $begin   = '2025-1-1';
    $end     = date('Y-m-d');
    $hightUv = 1000;

    $generator = new \Coco\wp\VisitorGenerator($matomoUrl, $matomoToken, $matomoSiteId, $wpUrl, $manager);

    $generator->setChunk(300);
    $generator->setEnableEchoLog(true);

    $generator->setPageName('hohogames');
    $generator->setTime($begin);
    $generator->setUv(0, $hightUv);
    $generator->update();

