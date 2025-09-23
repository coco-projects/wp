<?php

    namespace Coco\wp;

    class VisitorGeneratorGeneral extends VisitorGeneratorBase
    {
        public function __construct(int $siteId, Manager $wpManager, string $wpUrl, protected $initFunc = null, protected $addUvFunc = null, protected $writeRecordFunc = null,)
        {
            parent::__construct($siteId, $wpManager, $wpUrl);
        }

        public function listenDoWrite(): void
        {
            date_default_timezone_set('Asia/Shanghai');

            $this->makePagesData($this->initFunc, $this->addUvFunc, $this->writeRecordFunc);
        }
    }