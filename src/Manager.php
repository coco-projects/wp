<?php

    namespace Coco\wp;

    use Coco\tableManager\TableRegistry;
    use Coco\wp\tables\Commentmeta;
    use Coco\wp\tables\Comments;
    use Coco\wp\tables\Links;
    use Coco\wp\tables\Options;
    use Coco\wp\tables\Postmeta;
    use Coco\wp\tables\Posts;
    use Coco\wp\tables\Termmeta;
    use Coco\wp\tables\TermRelationships;
    use Coco\wp\tables\Terms;
    use Coco\wp\tables\TermTaxonomy;
    use Coco\wp\tables\Usermeta;
    use Coco\wp\tables\Users;
    use DI\Container;

    class Manager
    {
        protected ?Container $container = null;
        protected array      $tables    = [];

        protected bool $enableRedisLog = false;
        protected bool $enableEchoLog  = false;

        protected ?string $logNamespace;

        protected string $redisHost     = '127.0.0.1';
        protected string $redisPassword = '';
        protected int    $redisPort     = 6379;
        protected int    $redisDb       = 14;

        protected string $mysqlDb;
        protected string $mysqlHost     = '127.0.0.1';
        protected string $mysqlUsername = 'root';
        protected string $mysqlPassword = 'root';
        protected int    $mysqlPort     = 3306;

        public function __construct(protected string $redisNamespace, ?Container $container = null)
        {
            if (!is_null($container))
            {
                $this->container = $container;
            }
            else
            {
                $this->container = new Container();
            }

            $this->redisNamespace .= '-wp';
            $this->logNamespace   = $this->redisNamespace . ':tg-log:';
        }

        public function initServer(): static
        {
            $this->initMysql();
            $this->initRedis();

            return $this;
        }

        public function setEnableEchoLog(bool $enableEchoLog): static
        {
            $this->enableEchoLog = $enableEchoLog;

            return $this;
        }

        public function setEnableRedisLog(bool $enableRedisLog): static
        {
            $this->enableRedisLog = $enableRedisLog;

            return $this;
        }

        public function initTableStruct(string $tablePrefix = 'wp_'): void
        {
            $this->initCommentmetaTable($tablePrefix . 'commentmeta', function(Commentmeta $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('meta_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initCommentsTable($tablePrefix . 'comments', function(Comments $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('comment_ID');
                $table->setIsPkAutoInc(true);
            });

            $this->initLinksTable($tablePrefix . 'links', function(Links $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('link_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initOptionsTable($tablePrefix . 'options', function(Options $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('option_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initPostmetaTable($tablePrefix . 'postmeta', function(Postmeta $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('meta_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initPostsTable($tablePrefix . 'posts', function(Posts $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('ID');
                $table->setIsPkAutoInc(true);
            });

            $this->initTermRelationshipsTable($tablePrefix . 'term_relationships', function(TermRelationships $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('object_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initTermTaxonomyTable($tablePrefix . 'term_taxonomy', function(TermTaxonomy $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('term_taxonomy_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initTermmetaTable($tablePrefix . 'termmeta', function(Termmeta $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('meta_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initTermsTable($tablePrefix . 'terms', function(Terms $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('term_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initUsermetaTable($tablePrefix . 'usermeta', function(Usermeta $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('umeta_id');
                $table->setIsPkAutoInc(true);
            });

            $this->initUsersTable($tablePrefix . 'users', function(Users $table) {
                $registry = $table->getTableRegistry();

                $table->setPkField('ID');
                $table->setIsPkAutoInc(true);
            });

        }

        /*
        *
        * ------------------------------------------------------
        *
        * */

        public function setMysqlConfig($db, $host = '127.0.0.1', $username = 'root', $password = 'root', $port = 3306): static
        {
            $this->mysqlHost     = $host;
            $this->mysqlPassword = $password;
            $this->mysqlUsername = $username;
            $this->mysqlPort     = $port;
            $this->mysqlDb       = $db;

            return $this;
        }

        public function setRedisConfig(string $host = '127.0.0.1', string $password = '', int $port = 6379, int $db = 9): static
        {
            $this->redisHost     = $host;
            $this->redisPassword = $password;
            $this->redisPort     = $port;
            $this->redisDb       = $db;

            return $this;
        }

        public function getContainer(): Container
        {
            return $this->container;
        }

        public function getMysqlClient(): TableRegistry
        {
            return $this->container->get('mysqlClient');
        }

        protected function initMysql(): static
        {
            $this->container->set('mysqlClient', function(Container $container) {

                $registry = new TableRegistry($this->mysqlDb, $this->mysqlHost, $this->mysqlUsername, $this->mysqlPassword, $this->mysqlPort,);

                $logName = 'wp-log';
                $registry->setStandardLogger($logName);

                if ($this->enableRedisLog)
                {
                    $registry->addRedisHandler(redisHost: $this->redisHost, redisPort: $this->redisPort, password: $this->redisPassword, db: $this->redisDb, logName: $this->logNamespace . $logName, callback: TableRegistry::getStandardFormatter());
                }

                if ($this->enableEchoLog)
                {
                    $registry->addStdoutHandler(TableRegistry::getStandardFormatter());
                }

                return $registry;

            });

            return $this;
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */
        public function createAllTable($forceCreateTable = false): void
        {
            $this->getMysqlClient()->createAllTable($forceCreateTable);
        }

        public function dropAllTable(): void
        {
            $this->getMysqlClient()->dropAllTable();
        }

        public function truncateAllTable(): void
        {
            $this->getMysqlClient()->truncateAllTable();
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */
        public function initCommentmetaTable(string $name, callable $callback): static
        {
            $this->tables['Commentmeta'] = $name;

            $table = new Commentmeta($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getCommentmetaTable(): Commentmeta
        {
            return $this->getMysqlClient()->getTable($this->tables['Commentmeta']);
        }

        public function initCommentsTable(string $name, callable $callback): static
        {
            $this->tables['Comments'] = $name;

            $table = new Comments($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getCommentsTable(): Comments
        {
            return $this->getMysqlClient()->getTable($this->tables['Comments']);
        }

        public function initLinksTable(string $name, callable $callback): static
        {
            $this->tables['Links'] = $name;

            $table = new Links($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getLinksTable(): Links
        {
            return $this->getMysqlClient()->getTable($this->tables['Links']);
        }

        public function initOptionsTable(string $name, callable $callback): static
        {
            $this->tables['Options'] = $name;

            $table = new Options($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getOptionsTable(): Options
        {
            return $this->getMysqlClient()->getTable($this->tables['Options']);
        }

        public function initPostmetaTable(string $name, callable $callback): static
        {
            $this->tables['Postmeta'] = $name;

            $table = new Postmeta($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getPostmetaTable(): Postmeta
        {
            return $this->getMysqlClient()->getTable($this->tables['Postmeta']);
        }

        public function initPostsTable(string $name, callable $callback): static
        {
            $this->tables['Posts'] = $name;

            $table = new Posts($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getPostsTable(): Posts
        {
            return $this->getMysqlClient()->getTable($this->tables['Posts']);
        }

        public function initTermRelationshipsTable(string $name, callable $callback): static
        {
            $this->tables['TermRelationships'] = $name;

            $table = new TermRelationships($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getTermRelationshipsTable(): TermRelationships

        {
            return $this->getMysqlClient()->getTable($this->tables['TermRelationships']);
        }

        public function initTermTaxonomyTable(string $name, callable $callback): static
        {
            $this->tables['TermTaxonomy'] = $name;

            $table = new TermTaxonomy($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getTermTaxonomyTable(): TermTaxonomy
        {
            return $this->getMysqlClient()->getTable($this->tables['TermTaxonomy']);
        }

        public function initTermmetaTable(string $name, callable $callback): static
        {
            $this->tables['Termmeta'] = $name;

            $table = new Termmeta($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getTermmetaTable(): Termmeta
        {
            return $this->getMysqlClient()->getTable($this->tables['Termmeta']);
        }

        public function initTermsTable(string $name, callable $callback): static
        {
            $this->tables['Terms'] = $name;

            $table = new Terms($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getTermsTable(): Terms
        {
            return $this->getMysqlClient()->getTable($this->tables['Terms']);
        }

        public function initUsermetaTable(string $name, callable $callback): static
        {
            $this->tables['Usermeta'] = $name;

            $table = new Usermeta($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getUsermetaTable(): Usermeta
        {
            return $this->getMysqlClient()->getTable($this->tables['Usermeta']);
        }

        public function initUsersTable(string $name, callable $callback): static
        {
            $this->tables['Users'] = $name;

            $table = new Users($name);

            $this->getMysqlClient()->addTable($table, $callback);

            return $this;
        }

        public function getUsersTable(): Users
        {
            return $this->getMysqlClient()->getTable($this->tables['Users']);
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */

        public function addCategory($type): int|string
        {
            return $this->addTerm($type, 'category');
        }

        public function addNavMenu($type): int|string
        {
            return $this->addTerm($type, 'nav_menu');
        }

        public function addWpTheme($type): int|string
        {
            return $this->addTerm($type, 'wp_theme');
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */

        public function addTerm($type, $taxonomy): int|string
        {
            $termTable         = $this->getTermsTable();
            $termTaxonomyTable = $this->getTermTaxonomyTable();

            $typeId = $termTable->tableIns()->insertGetId([
                $termTable->getNameField()      => $type,
                $termTable->getSlugField()      => 'type-' . hrtime(true),
                $termTable->getTermGroupField() => 0,
            ]);

            return $termTaxonomyTable->tableIns()->insertGetId([
                $termTaxonomyTable->getTermIdField()   => $typeId,
                $termTaxonomyTable->getTaxonomyField() => $taxonomy,
            ]);
        }

        public function addPost(string $title, string $postContent, string $typeId, string $guid = null): int|string
        {
            if (is_null($guid))
            {
                $guid = hrtime(true);
            }

            $termRelationshipsTable = $this->getTermRelationshipsTable();
            $postsTable             = $this->getPostsTable();

            // 获取当前本地时间
            $local_time = new \DateTime();
            $local_time->setTimezone(new \DateTimeZone('Asia/Shanghai')); // 设置为本地时区（例如上海时区）
            $formatted_post_date = $local_time->format('Y-m-d H:i:s');    // 本地时间，符合 'Y-m-d H:i:s' 格式

            // 获取当前的 GMT 时间
            $gmt_time = new \DateTime();
            $gmt_time->setTimezone(new \DateTimeZone('GMT'));            // 设置为 GMT 时区
            $formatted_post_date_gmt = $gmt_time->format('Y-m-d H:i:s'); // GMT 时间，符合 'Y-m-d H:i:s' 格式

            $postId = $postsTable->tableIns()->insertGetId([
                $postsTable->getPostAuthorField()          => 1,
                $postsTable->getPostDateField()            => $formatted_post_date,
                $postsTable->getPostDateGmtField()         => $formatted_post_date_gmt,
                $postsTable->getPostContentField()         => (string)$postContent,
                $postsTable->getPostTitleField()           => $title,
                $postsTable->getPostExcerptField()         => '',
                $postsTable->getPostStatusField()          => 'publish',
                $postsTable->getCommentStatusField()       => 'open',
                $postsTable->getPingStatusField()          => 'open',
                $postsTable->getPostPasswordField()        => '',
                $postsTable->getPostNameField()            => 'post-' . hrtime(true),
                $postsTable->getToPingField()              => '',
                $postsTable->getPingedField()              => '',
                $postsTable->getPostModifiedField()        => $formatted_post_date,
                $postsTable->getPostModifiedGmtField()     => $formatted_post_date_gmt,
                $postsTable->getPostContentFilteredField() => '',
                $postsTable->getPostParentField()          => 0,
                $postsTable->getGuidField()                => $guid,
                $postsTable->getMenuOrderField()           => 0,
                $postsTable->getPostTypeField()            => 'post',
                $postsTable->getPostMimeTypeField()        => 0,
                $postsTable->getCommentCountField()        => 0,

            ]);

            return $termRelationshipsTable->tableIns()->insertGetId([
                $termRelationshipsTable->getObjectIdField()       => $postId,
                $termRelationshipsTable->getTermTaxonomyIdField() => $typeId,
            ]);
        }

        public function updatePostByGuid(string $guid, string $title = null, string $postContent = null): int
        {
            $postsTable = $this->getPostsTable();

            $data = [];

            if (!is_null($title))
            {
                $data[$postsTable->getPostTitleField()] = $title;
            }
            if (!is_null($postContent))
            {
                $data[$postsTable->getPostContentField()] = $postContent;
            }

            return $postsTable->tableIns()->where([
                [
                    $postsTable->getGuidField(),
                    '=',
                    $guid,
                ],
            ])->update($data);
        }

        public function updatePostById($id, string $title = null, string $postContent = null): int
        {
            $postsTable = $this->getPostsTable();

            $data = [];

            if (!is_null($title))
            {
                $data[$postsTable->getPostTitleField()] = $title;
            }
            if (!is_null($postContent))
            {
                $data[$postsTable->getPostContentField()] = $postContent;
            }

            return $postsTable->tableIns()->where([
                [
                    $postsTable->getPkField(),
                    '=',
                    $id,
                ],
            ])->update($data);
        }

        public function deleteTransient(): int|string
        {
            $optionTable = $this->getOptionsTable();

            return $optionTable->tableIns()->where([
                [
                    $optionTable->getOptionNameField(),
                    'like',
                    '%_transient_%',
                ],
            ])->delete();
        }

        public function updateOptionByName($name, $value): int|string
        {
            $optionTable = $this->getOptionsTable();
            $optionTable->tableIns()->where([
                [
                    $optionTable->getOptionNameField(),
                    '=',
                    $name,
                ],
            ])->update([
                $optionTable->getOptionValueField() => $value,
            ]);
        }

        public function getOptionByName($name): int|string
        {
            $optionTable = $this->getOptionsTable();

            return $optionTable->tableIns()->where([
                [
                    $optionTable->getOptionNameField(),
                    '=',
                    $name,
                ],
            ])->find();
        }

        public function getAllOptions(): \think\model\Collection|array|\think\Collection
        {
            $optionTable = $this->getOptionsTable();

            return $optionTable->tableIns()->select();
        }

        public function replaceAll(array $replacement): void
        {
            uksort($replacement, function($a, $b) {
                return mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8');  // 按照字符长度降序排列
            });

            foreach ($replacement as $name => $value)
            {
                $this->getMysqlClient()->logInfo('【' . $name . ' ----> ' . $value . '】');

                $this->getMysqlClient()->logInfo('【replaceOptions】');
                $this->replaceOptions($name, $value);

                $this->getMysqlClient()->logInfo('【replaceUsers】');
                $this->replaceUsers($name, $value);

                $this->getMysqlClient()->logInfo('【replacePosts】');
                $this->replacePosts($name, $value);

                $this->getMysqlClient()->logInfo('【replacePostmeta】');
                $this->replacePostmeta($name, $value);
            }
        }

        public function replacePosts($form, $to): void
        {
            $postsTable = $this->getPostsTable();

            $result = $postsTable->tableIns()->field('ID,post_excerpt,post_content,guid')->select();

            foreach ($result as $item)
            {
                $data = [
                    "post_excerpt" => $item['post_excerpt'],
                    "post_content" => $item['post_content'],
                    "guid"         => $item['guid'],
                ];

                foreach ($data as $k => &$v)
                {
                    $v = strtr($v, [$form => $to]);
                }

                $this->getMysqlClient()->logInfo('posts: ' . $item['ID']);

                $postsTable->tableIns()->where('ID', $item['ID'])->update($data);
            }
        }

        public function replaceUsers($form, $to): void
        {
            $usersTable = $this->getUsersTable();

            $result = $usersTable->tableIns()->field('ID,user_url')->select();

            foreach ($result as $item)
            {
                $data = [
                    "user_url" => $item['user_url'],
                ];

                foreach ($data as $k => &$v)
                {
                    $v = strtr($v, [$form => $to]);
                }
                $this->getMysqlClient()->logInfo('users: ' . $item['ID']);

                $usersTable->tableIns()->where('ID', $item['ID'])->update($data);
            }
        }

        public function replaceOptions($form, $to): void
        {
            $optionsTable = $this->getOptionsTable();

            $result     = $optionsTable->tableIns()->select();
            $newOptions = [];

            // 遍历每个选项
            // 将序列化过的值反序列化后，修改过来，再序列化后更新进去
            foreach ($result as $item)
            {
                if (preg_match('/^[a-z]:\d+/i', $item['option_value']))
                {
                    //值是被序列化过的选项
                    if (preg_match('/^a:\d+/i', $item['option_value']))
                    {
                        $value = unserialize($item['option_value']);

                        if (!is_array($value))
                        {
                            $newOptions[$item['option_name']] = $item['option_value'];

                            $this->getMysqlClient()->logInfo('反序列化出错：' . $item['option_value']);

                            continue;
                        }

                        $tMap = [];

                        $tMap[strtr($form, ["/" => "\/"])] = strtr($to, ["/" => "\/"]);

                        $json  = json_encode($value, 256);
                        $json  = strtr($json, $tMap);
                        $value = json_decode($json, 1);

                        $newOptions[$item['option_name']] = serialize($value);
                    }
                }
                else
                {
                    $newOptions[$item['option_name']] = strtr($item['option_value'], [$form => $to]);
                }
            }

            foreach ($newOptions as $option_name => $option_value)
            {
                $this->getMysqlClient()->logInfo('options: ' . $option_name);

                $optionsTable->tableIns()->where('option_name', $option_name)
                    ->update(['option_value' => $option_value]);
            }
        }


        public function replacePostmeta($form, $to): void
        {
            $postmetaTable = $this->getPostmetaTable();

            $result     = $postmetaTable->tableIns()->select();
            $newOptions = [];

            // 遍历每个选项
            // 将序列化过的值反序列化后，修改过来，再序列化后更新进去
            foreach ($result as $item)
            {
                if (preg_match('/^[a-z]:\d+/i', $item['meta_value']))
                {
                    //值是被序列化过的选项
                    if (preg_match('/^a:\d+/i', $item['meta_value']))
                    {
                        $value = unserialize($item['meta_value']);

                        if (!is_array($value))
                        {
                            $newOptions[$item['meta_key']] = $item['meta_value'];

                            $this->getMysqlClient()->logInfo('反序列化出错：' . $item['meta_key']);

                            continue;
                        }

                        $tMap = [];

                        $tMap[strtr($form, ["/" => "\/"])] = strtr($to, ["/" => "\/"]);

                        $json  = json_encode($value, 256);
                        $json  = strtr($json, $tMap);
                        $value = json_decode($json, 1);

                        $newOptions[$item['meta_key']] = serialize($value);
                    }
                }
                else
                {
                    $newOptions[$item['meta_key']] = strtr($item['meta_value'], [$form => $to]);
                }
            }

            foreach ($newOptions as $meta_key => $meta_value)
            {
                $this->getMysqlClient()->logInfo('meta_key: ' . $meta_key);

                $postmetaTable->tableIns()->where('meta_key', $meta_key)->update(['meta_value' => $meta_value]);
            }
        }

        protected function initRedis(): static
        {
            $this->container->set('redisClient', function(Container $container) {

                /**
                 * @var \Redis $redis
                 */
                $redis = (new \Redis());
                $redis->connect($this->redisHost, $this->redisPort);
                $this->redisPassword && $redis->auth($this->redisPassword);
                $redis->select($this->redisDb);

                return $redis;
            });

            return $this;
        }

        public function getRedisClient(): \Redis
        {
            return $this->container->get('redisClient');
        }

        public function deleteRedisLog(): void
        {
            $redis = $this->getRedisClient();

            $pattern = $this->logNamespace . '*';

            $keysToDelete = $redis->keys($pattern);

            foreach ($keysToDelete as $key)
            {
                $redis->del($key);
            }
        }


    }