<?php

    use Coco\wp\VisitorGeneratorByWebApi;

    require 'common.php';
    @date_default_timezone_set('UTC');

    $matomoSiteId = 1;
    $apiUrl       = "http://dev6058/";
    $matomoToken  = "c5bb885c94da01883e26573827c63874";

    $wpUrl = "http://dev6080/";

    //开始时间可以精确到时分秒
    $startTime = '2025-04-10';
    $lowUv     = 80;

    //结束时间不用写时分秒，始终到当天晚上 23:59:59
    $endTime = '2025-08-25';
    $aYearUv = 500;

    $generator = new VisitorGeneratorByWebApi($matomoSiteId, $manager, $wpUrl, $apiUrl, $matomoToken);
    $generator->setChunkSize(300);
    $generator->setEnableEchoLog(true);
    $generator->setPageName('hohogames');
    $generator->setStartTime($startTime);
    $generator->setEndTime($endTime);
    $generator->setLowUv($lowUv);
    $generator->setHightUv($aYearUv);

    $generator->listenDoWrite();
