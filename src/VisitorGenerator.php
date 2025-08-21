<?php

    namespace Coco\wp;

    use Coco\logger\Logger;
    use Coco\matomo\MatomoClient;
    use Coco\matomo\Uv;

    class VisitorGenerator
    {
        use Logger;

        public MatomoClient $client;

        protected string $startTime;
        protected string $endTime;
        protected string $pageName;

        protected int $lowUv         = 0;
        protected int $hightUv       = 1000;
        protected int $rowPage       = 20;
        private bool  $enableEchoLog = false;

        public function __construct(string $apiUrl, string $token, int $siteId, protected string $wpUrl, public Manager $wpManager)
        {
            $this->client = MatomoClient::getClient($apiUrl, $token, $siteId);
            $this->wpUrl  = trim($wpUrl, '/');
        }

        public function setRowPage(int $rowPage): static
        {
            $this->rowPage = $rowPage;

            return $this;
        }

        public function setChunk(int $chunk): static
        {
            $this->client->setChunkSize($chunk);

            return $this;
        }

        public function setEnableEchoLog(bool $enable = true): static
        {
            $this->enableEchoLog = $enable;
            MatomoClient::enableEchoLog($this->enableEchoLog);

            $this->setStandardLogger('VisitorGeneratorLog');
            $this->addStdoutHandler(callback: $this::getStandardFormatter());

            return $this;
        }

        public function setPageName(string $pageName): static
        {
            $this->pageName = $pageName;

            return $this;
        }

        public function setWpUrl(string $wpUrl): static
        {
            $this->wpUrl = $wpUrl;

            return $this;
        }

        /**
         * 启动后不能关闭，必须常驻内存运行
         * 生成实时访问记录，持续向后生成
         *
         *
         * @param string $startTime 开始时间可以精确到时分秒
         * @param string $endTime   结束时间不用写时分秒，始终到当天晚上 23:59:59
         * @param int    $lowUv
         * @param int    $aYearUv
         *
         * @return void
         */
        public function updateLive(string $startTime, string $endTime, int $lowUv, int $aYearUv): void
        {
            //延迟多少秒发送
            $delay           = 60;
            $intervalSeconds = 2;

            $this->startTime = $startTime;
            $this->endTime   = $endTime;

            $this->lowUv   = $lowUv;
            $this->hightUv = $aYearUv;

            $allPages = $this->makePageUrls();

            // 生成开始到结束所有天数
            $dateRange = static::getDateRange($this->startTime, $this->endTime);
            $totalDays = count($dateRange);

            $uvList = static::generateTrafficData($this->lowUv, $this->hightUv, count($dateRange), 50);
            $h      = implode(',', $uvList);

            $this->logInfo('共:' . $totalDays . '天');

            foreach ($dateRange as $k => $day)
            {
                $uvs = [];

                //对每天生成指定次数的访问时间
                $timeNodes  = static::generateRandomTimes($day, $uvList[$k]);
                $totalNodes = count($timeNodes);

                $this->logInfo($day . ' 共:' . $totalNodes . ' UV');

                foreach ($timeNodes as $t1 => $time)
                {
                    //一个时间代表一个uv，每个uv访问随机几次
                    $session = \Coco\matomo\Session::newIns()->randomDevice();
                    $uv      = new Uv($time);
                    $uv->setSession($session);

                    $pageTimes = rand(1, 5);

                    $h = 10;
                    if (!(rand(1, $h) % $h))
                    {
                        $pageTimes = rand(6, 10);
                    }

                    $h = 20;
                    if (!(rand(1, $h) % $h))
                    {
                        $pageTimes = rand(11, 15);
                    }

                    $h = 50;
                    if (!(rand(1, $h) % $h))
                    {
                        $pageTimes = rand(16, 20);
                    }

                    for ($i = 0; $i < $pageTimes; $i++)
                    {
                        //随机获取一个访问地址
                        $randomKey = array_rand($allPages);
                        $urlInfo   = $allPages[$randomKey];

                        //随机生成一个referer，3种可能：没有，搜索引擎，站内页面过来
                        $seek = rand(1, 3);
                        if ($seek == 1)
                        {
                            $referer = $session->faker->searchEngineUrlWithOutKeyword();
                        }
                        elseif ($seek == 2)
                        {
                            $referer = '';
                        }
                        else
                        {
                            $randomKey1  = array_rand($allPages);
                            $refererInfo = $allPages[$randomKey1];

                            $referer = $refererInfo['url'];
                        }

                        $pv = new \Coco\matomo\Pv();
                        $t  = explode(' ', $time);
                        $pv->setLocalTime($t[1]);

                        $pv->setForceVisitDateTime($time);

                        $referer && $pv->setUrlReferrer($referer);
                        $pv->setPageUrl($urlInfo['url']);
                        $pv->getUrlTrackPageView($urlInfo['title']);

                        $time = date('Y-m-d H:i:s', strtotime($time) + rand(1, 50));

                        $uv->addPv($pv);
                    }

                    $this->logInfo('第(' . ($k + 1) . '/' . $totalDays . ')天, ' . $time . ', 访问UV: ' . ($t1 + 1) . '/' . $totalNodes . ', 此UV的PV: ' . $uv->getPvCount());
                    $uvs[] = $uv;
                }

                $lastTime = 0;
                while (count($uvs))
                {
                    $uvObj = null;
                    $count = 0;
                    while ($uvObj = array_shift($uvs))
                    {
                        $lastPvTime = $uvObj->getLastViewTime();

                        if (strtotime($lastPvTime) < (time() - $delay))
                        {
//                            $this->logInfo('首pv:（' . $uvObj->getViewTime() . '），尾pv:（' . $lastPvTime . '）, pv数: ' . $uvObj->getPvCount() . ', sessionId: ' . $uvObj->getSessionId());

                            $this->client->addUv($uvObj);
                            $uvObj = null;

                            $count++;

                            $intervalSeconds = 0;
                        }
                        else
                        {
                            $this->logInfo('下次:（' . $lastPvTime . '）,还剩:' . (strtotime($lastPvTime) - time() + $delay));

                            array_unshift($uvs, $uvObj);
                            $uvObj = null;

                            $intervalSeconds = 60;
                            break;
                        }
                    }

                    if ($count)
                    {
                        $this->logInfo($count . ' 个UV发送中...');
                        $this->client->sendRequest();
                        $this->logInfo('发送完成');
                    }

                    if ($intervalSeconds)
                    {
                        $this->logInfo("等{$intervalSeconds}秒...");
                        sleep($intervalSeconds);
                    }
                }

            }
        }

        private function makePageUrls(): array
        {
            $wpPostTab = $this->wpManager->getPostsTable();

            $wpIds = $wpPostTab->tableIns()->where([
                [
                    $wpPostTab->getGuidField(),
                    'regexp',
                    '^[0-9]{18,20}$',
                ],
            ])->field([
                $wpPostTab->getPkField(),
                $wpPostTab->getPostTitleField(),
            ])->order($wpPostTab->getGuidField())->select();

            $totalRow  = count($wpIds);
            $totalPage = ceil($totalRow / $this->rowPage);

            $allPages = [];

            //主页
            $allPages[] = [
                "title" => $this->pageName,
                "url"   => $this->wpUrl,
            ];

            //分页
            for ($i = 1; $i <= $totalPage; $i++)
            {
                $allPages[] = [
                    "title" => $this->pageName . '-Page ' . $i,
                    "url"   => $this->wpUrl . '/page/' . $i,
                ];
            }

            //详细页面
            foreach ($wpIds as $k => $v)
            {
                $allPages[] = [
                    "title" => $v[$wpPostTab->getPostTitleField()] . ' - ' . $this->pageName,
                    "url"   => $this->wpUrl . '/archives/' . $v[$wpPostTab->getPkField()],
                ];
            }

            return $allPages;
        }

        private static function getDateRange($startDate, $endDate): array
        {
            // 转换字符串为时间戳
            $start = strtotime($startDate);
            $end   = strtotime(date('Y-m-d', strtotime($endDate)) . ' 23:59:59');

            $dates = [];

            // 循环遍历日期区间
            while ($start <= $end)
            {
                // 将时间戳格式化为 YYYY-m-d 格式并添加到数组
                $dates[] = date('Y-m-d H:i:s', $start);

                // 增加一天
                $start = strtotime('+1 day', strtotime(date('Y-m-d', $start)));
            }

            return $dates;
        }

        private static function generateRandomTimes($date, $count): array
        {
            // 获取日期的开始和结束时间
            $startTimestamp = strtotime($date);
            $endTimestamp   = strtotime(explode(' ', $date)[0] . ' 23:59:59');

            $randomTimes = [];

            // 生成指定次数的随机时间
            for ($i = 0; $i < $count; $i++)
            {
                // 随机生成一个时间戳
                $randomTimestamp = rand($startTimestamp, $endTimestamp);

                // 将时间戳格式化为 'Y-m-d H:i:s' 格式
                $randomTimes[] = date('Y-m-d H:i:s', $randomTimestamp);
            }

            sort($randomTimes);

            return $randomTimes;
        }

        /**
         * 曲线图调试工具 https://www.tubiaoyi.com/smooth-line/
         *
         *
         * @param int $start
         * @param int $end
         * @param int $days
         * @param int $initialFluctuationRange 波动幅度
         *
         * @return array
         */
        private static function generateTrafficData(int $start, int $end, int $days, int $initialFluctuationRange = 2): array
        {
            // 初始化一个空数组来存储访问量数据
            $trafficData = [];

            // 计算整体的增长趋势
            $totalIncrease = $end - $start;

            // 生成每天的访问量
            for ($i = 0; $i < $days; $i++)
            {
                // 基于增长速度调整增长的幅度
                $growthFactor = ($i / $days);

                // 计算当天的基本访问量
                $basicTraffic = $start + ($totalIncrease * $growthFactor);

                // 控制波动范围（波动范围随着天数逐渐增大）
                $fluctuationRange = $initialFluctuationRange * exp($i / $days);      // 使用指数增长模拟波动

                // 随机确定波动方向：有可能是正向（增长），也有可能是负向（回落）
                $randomFactor = rand(0, 1) == 0 ? -1 : 1;                            // 随机决定是增加还是减少访问量

                // 给访问量加上一个随机的波动（模拟真实的变化）
                $fluctuation = rand(0, (int)$fluctuationRange) * $randomFactor;      // 随机决定波动方向

                // 确保访问量不为负数
                $traffic = max(0, $basicTraffic + $fluctuation);

                //有12分之一的几率某天访问量突然降低到0.2到0.8
                $h = 12;
                if (!(rand(1, $h) % $h))
                {
                    $traffic *= (rand(2, 8) / 10);
                }

                //有50分之一的几率某天访问量突然上涨到1.2到1.5
                $l = 50;
                if (!(rand(1, $l) % $l))
                {
                    $traffic *= 1 + (rand(2, 5) / 10);
                }

                // 将生成的访问量添加数组
                $trafficData[] = (int)round($traffic);
            }

            return $trafficData;
        }


    }