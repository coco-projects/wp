<?php

    use Coco\matomo\MatomoClient;
    use Coco\matomo\MatomoWebApiClient;
    use Coco\matomo\Uv;
    use Coco\wp\VisitorGeneratorGeneral;

    require 'common.php';
    @date_default_timezone_set('UTC');

    $matomoSiteId = 1;
    $apiUrl       = "http://dev6058/";
    $matomoToken  = "c5bb885c94da01883e26573827c63874";

    $wpUrl = "http://dev6080/";

    //开始时间可以精确到时分秒
    $startTime = '2025-09-20';
    $lowUv     = 80;

    //结束时间不用写时分秒，始终到当天晚上 23:59:59
    $endTime = '2025-09-25';
    $aYearUv = 5000;

    $initFunc = function(VisitorGeneratorGeneral $_this) {
        MatomoWebApiClient::initLogger('VisitorGeneratorByInsertToDb', $_this->enableEchoLog);

        $_this->client = new MatomoClient($_this->siteId);
        $_this->client->setChunkSize($_this->chunkSize);
    };

    $addUvFunc = function(Uv $uvObj, VisitorGeneratorGeneral $_this) {
        $_this->client->addUv($uvObj);
    };

    $writeRecordFunc = function(VisitorGeneratorGeneral $_this) {

        $_this->client->eachChunks(function($uvsChunk, $k) use (&$_this) {

            $data = [];
            foreach ($uvsChunk as $k => $uv)
            {
                $data[] = $uv->makeDataPair($_this->siteId);

                echo $uv->getLastViewTime();
                echo PHP_EOL;
            }

//            print_r($data);

        });

    };

    $generator = new VisitorGeneratorGeneral($matomoSiteId, $manager, $wpUrl, $initFunc, $addUvFunc, $writeRecordFunc);
    $generator->setChunkSize(300);
    $generator->setEnableEchoLog(true);
    $generator->setPageName('hohogames');
    $generator->setStartTime($startTime);
    $generator->setEndTime($endTime);
    $generator->setLowUv($lowUv);
    $generator->setHightUv($aYearUv);

    $generator->listenDoWrite();
