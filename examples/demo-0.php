<?php

    require 'common.php';

    $matomoSiteId = 17;
    $matomoUrl    = "http://dev6058/";
    $matomoToken  = "28b96e44af9997f60808c4cafe652ace";

    $wpUrl = "http://dev6080/";

    //开始时间可以精确到时分秒
    $startTime = '2025-02-13';
    $lowUv     = 80;

    //结束时间不用写时分秒，始终到当天晚上 23:59:59
    $endTime = '2025-08-25';
    $aYearUv = 500;

    $generator = new \Coco\wp\VisitorGenerator($matomoUrl, $matomoToken, $matomoSiteId, $wpUrl, $manager);
    $generator->setChunk(300);
    $generator->setEnableEchoLog(true);
    $generator->setPageName('hohogames');

    $generator->updateLive($startTime, $endTime, $lowUv, $aYearUv);
