<?php

    namespace Coco\wp;

    use Coco\matomo\MatomoWebApiClient;
    use Coco\matomo\Uv;

    class VisitorGeneratorByWebApi extends VisitorGeneratorBase
    {
        public function __construct(int $siteId, Manager $wpManager, string $wpUrl, protected string $apiUrl, protected string $token)
        {
            parent::__construct($siteId, $wpManager, $wpUrl);
            $this->wpUrl = trim($this->wpUrl, '/');
        }

        public function listenDoWrite(): void
        {
            $initFunc = function(VisitorGeneratorByWebApi $_this) {
                MatomoWebApiClient::initLogger('VisitorGeneratorByWebApi', $_this->enableEchoLog);
                $_this->client = MatomoWebApiClient::getClient($_this->apiUrl, $_this->token, $_this->siteId);
                $_this->client->setChunkSize($_this->chunkSize);
            };

            $addUvFunc = function(Uv $uvObj, VisitorGeneratorByWebApi $_this) {
                $_this->client->addUv($uvObj);
            };

            $writeRecordFunc = function(VisitorGeneratorByWebApi $_this) {
                $_this->client->sendRequest();
            };

            $this->makePagesData($initFunc, $addUvFunc, $writeRecordFunc,);
        }
    }