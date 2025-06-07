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

        public function getRedisClient(): \Redis
        {
            return $this->container->get('redisClient');
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

        public function addCategory($name): int|string
        {
            return $this->addTerm($name, 'category');
        }

        public function addNavMenu($name): int|string
        {
            return $this->addTerm($name, 'nav_menu');
        }

        public function addWpTheme($name): int|string
        {
            return $this->addTerm($name, 'wp_theme');
        }

        public function addPostTag($name): int|string
        {
            return $this->addTerm($name, 'post_tag');
        }

        public function addTopics($name): int|string
        {
            return $this->addTerm($name, 'topics');
        }


        public function getCategory($names = []): array
        {
            return $this->getTermsByTaxonomy('category', $names);
        }

        public function getNavMenu($names = []): array
        {
            return $this->getTermsByTaxonomy('nav_menu', $names);
        }

        public function getWpTheme($names = []): array
        {
            return $this->getTermsByTaxonomy('wp_theme', $names);
        }

        public function getPostTag($names = []): array
        {
            return $this->getTermsByTaxonomy('post_tag', $names);
        }

        public function getTopics($names = []): array
        {
            return $this->getTermsByTaxonomy('topics', $names);
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */

        protected function addTerm($name, $taxonomy): int|string
        {
            $termTable         = $this->getTermsTable();
            $termTaxonomyTable = $this->getTermTaxonomyTable();

            $termId = $termTable->tableIns()->insertGetId([
                $termTable->getNameField()      => $name,
                $termTable->getSlugField()      => 'term-' . hrtime(true),
                $termTable->getTermGroupField() => 0,
            ]);

            return $termTaxonomyTable->tableIns()->insertGetId([
                $termTaxonomyTable->getTermIdField()   => $termId,
                $termTaxonomyTable->getTaxonomyField() => $taxonomy,
            ]);
        }

        public function getTermsByTaxonomy(string $taxonomy, array $names): array
        {
            $termTaxonomyTable = $this->getTermTaxonomyTable();
            $termTable         = $this->getTermsTable();

            $whereNames = [];

            if (count($names))
            {
                $whereNames = [
                    [
                        $termTable->getName() . '.' . $termTable->getNameField(),
                        'in',
                        $names,
                    ],
                ];
            }

            /*
SELECT
  `wp_term_taxonomy`.`term_taxonomy_id`,
  wp_terms.name AS term_name
FROM
  `wp_term_taxonomy`
  LEFT JOIN `wp_terms`
    ON `wp_term_taxonomy`.`term_id` = `wp_terms`.`term_id`
WHERE `wp_term_taxonomy`.`taxonomy` = 'category'
            */
            $items = $termTaxonomyTable->tableIns()->field(implode(',', [
                $termTaxonomyTable->getName() . '.' . $termTaxonomyTable->getTermTaxonomyIdField(),
                $termTable->getName() . '.' . $termTable->getNameField() . ' as term_name',
            ]))->where($termTaxonomyTable->getName() . '.' . $termTaxonomyTable->getTaxonomyField(), '=', $taxonomy)
                ->where($whereNames)
                ->join($termTable->getName(), $termTaxonomyTable->getName() . '.' . $termTaxonomyTable->getTermIdField() . ' = ' . $termTable->getName() . '.' . $termTable->getTermIdField(), 'left')
                ->select();

            $result = [];

            foreach ($items as $k => $v)
            {
                $result[$v[$termTaxonomyTable->getTermTaxonomyIdField()]] = $v['term_name'];
            }

            return $result;
        }

        public function addPostTerm($postId, $termTaxonomyId, $order = 0): int|string
        {
            $termRelationshipsTable = $this->getTermRelationshipsTable();

            return $termRelationshipsTable->tableIns()->insertGetId([
                $termRelationshipsTable->getObjectIdField()       => $postId,
                $termRelationshipsTable->getTermTaxonomyIdField() => $termTaxonomyId,
                $termRelationshipsTable->getTermOrderField()      => $order,
            ]);
        }

        public function importPostTerm($postId, $termTaxonomyIdArray): int|string
        {
            $termRelationshipsTable = $this->getTermRelationshipsTable();

            $data = [];
            foreach ($termTaxonomyIdArray as $termTaxonomyId)
            {
                $data[] = [
                    $termRelationshipsTable->getObjectIdField()       => $postId,
                    $termRelationshipsTable->getTermTaxonomyIdField() => $termTaxonomyId,
                    $termRelationshipsTable->getTermOrderField()      => 1,
                ];
            }

            return $termRelationshipsTable->tableIns()->insertAll($data);
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */

        //根据传入的tags，写入数据库，返回这些tags的id
        public function addTags(array $tags): array
        {
            $tagsArray = $this->getPostTag();

            $tagsInDb = array_values($tagsArray);

            $newTags = array_diff($tags, $tagsInDb);

            foreach ($newTags as $k => $name)
            {
                $this->addPostTag($name);
            }

            return $this->getTagsIds($tags);
        }

        //根据传入的tags，返回这些tags的id
        public function getTagsIds(array $tags): array
        {
            $tagsArray = $this->getPostTag($tags);

            $tagIds = array_keys($tagsArray);

            return $tagIds;
        }

        public function deleteAllTags(): void
        {
            $termTaxonomyTable      = $this->getTermTaxonomyTable();
            $termTable              = $this->getTermsTable();
            $termmetaTable          = $this->getTermmetaTable();
            $termRelationshipsTable = $this->getTermRelationshipsTable();

            $data = $termTaxonomyTable->tableIns()->field([
                $termTaxonomyTable->getTermIdField(),
                $termTaxonomyTable->getTermTaxonomyIdField(),
            ])->where($termTaxonomyTable->getTaxonomyField(), '=', 'post_tag')->select();

            $term_ids          = [];
            $term_taxonomy_ids = [];

            foreach ($data as $k => $v)
            {
                $term_ids[]          = $v[$termTaxonomyTable->getTermIdField()];
                $term_taxonomy_ids[] = $v[$termTaxonomyTable->getTermTaxonomyIdField()];
            }

            $termTaxonomyTable->tableIns()->where($termTaxonomyTable->getTermIdField(), 'in', $term_ids)->delete();

            $termTable->tableIns()->where($termTable->getTermIdField(), 'in', $term_ids)->delete();

            $termmetaTable->tableIns()->where($termmetaTable->getTermIdField(), 'in', $term_ids)->delete();

            $termRelationshipsTable->tableIns()
                ->where($termRelationshipsTable->getTermTaxonomyIdField(), 'in', $term_taxonomy_ids)->delete();
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */

        /**
         *
         *
         * @param string      $title
         * @param string      $postContent
         * @param string      $typeId 为 terms 表的 term_id
         * @param string|null $guid
         *
         * @return int|string 返回当前post 的id
         */
        public function addPost(string $title, string $postContent, string $typeId, string $guid = null): int|string
        {
            if (is_null($guid))
            {
                $guid = hrtime(true);
            }

            $postsTable        = $this->getPostsTable();
            $termTaxonomyTable = $this->getTermTaxonomyTable();

            // 获取当前本地时间
            $local_time = new \DateTime();
            $local_time->setTimezone(new \DateTimeZone('Asia/Shanghai')); // 设置为本地时区（例如上海时区）
            $formatted_post_date = $local_time->format('Y-m-d H:i:s');    // 本地时间，符合 'Y-m-d H:i:s' 格式

            // 获取当前的 GMT 时间
            $gmt_time = new \DateTime();
            $gmt_time->setTimezone(new \DateTimeZone('GMT'));            // 设置为 GMT 时区
            $formatted_post_date_gmt = $gmt_time->format('Y-m-d H:i:s'); // GMT 时间，符合 'Y-m-d H:i:s' 格式

            $postData = [
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
            ];
            $postId   = $postsTable->tableIns()->insertGetId($postData);

            $termTaxonomyId = $termTaxonomyTable->tableIns()->where($termTaxonomyTable->getTermIdField(), '=', $typeId)
                ->value($termTaxonomyTable->getTermTaxonomyIdField());

            $this->addPostTerm($postId, (int)$termTaxonomyId);

            return $postId;
        }

        public function updatePostContentByGuid(string $guid, string $title = null, string $postContent = null): int
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

        public function updatePostContentById($id, string $title = null, string $postContent = null): int
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

        /**
         * 付费购买隐藏模块，文章内容中必须有隐藏模块 使用 hideContent 方法生产
         *
         * @param int|string $post_id
         * @param float      $zibpay_price
         * @param string     $pay_title
         * @param string     $pay_doc
         * @param string     $pay_details
         * @param int        $pay_limit 0->所有人可买,1->黄金会员及以上会员可购买, 2->仅钻石会员可购买
         * @param int        $pay_cuont
         *
         * @return void
         */
        public function makePostPayRead(int|string $post_id, float $zibpay_price, string $pay_title = '', string $pay_doc = '', string $pay_details = '', int $pay_limit = 0, int $pay_cuont = 0): void
        {
            $zibpay_type = 1;
            $pay_modo    = 0;

            $posts_zibpay = [
                'pay_type'            => $zibpay_type,
                'pay_limit'           => $pay_limit,
                'pay_modo'            => $pay_modo,
                'pay_price'           => $zibpay_price,
                'vip_1_price'         => ceil($zibpay_price * 0.8),
                'vip_2_price'         => ceil($zibpay_price * 0.6),
                'pay_original_price'  => ceil($zibpay_price * 1.6),
                //                'promotion_tag'       => '<i class="fa fa-fw fa-bolt"></i> 会员购买更便宜',
                'promotion_tag'       => '',
                'points_price'        => '',
                'vip_1_points'        => '',
                'vip_2_points'        => '',
                'pay_rebate_discount' => '',
                'pay_cuont'           => $pay_cuont,
                'pay_extra_hide'      => '',
                'pay_title'           => $pay_title,
                'pay_doc'             => $pay_doc,
                'pay_details'         => $pay_details,

                'pay_gallery'      => '',
                'pay_gallery_show' => '',

                'video_url'          => '',
                'video_pic'          => '',
                'video_title'        => '',
                'video_episode'      => [],
                'video_scale_height' => '0',

                'pay_download' => [],
                'attributes'   => [],
                'demo_link'    => [],
            ];

            $this->setPostPayInfo($post_id, $zibpay_type, $pay_modo, $zibpay_price, 0, $posts_zibpay);
        }

        /**
         * @param int|string $post_id
         * @param float      $zibpay_price
         * @param array      $pay_download
         * @param string     $pay_title
         * @param string     $pay_doc
         * @param string     $pay_details
         * @param int        $pay_limit
         * @param int        $pay_cuont
         *
         * @return void
         */
        public function makePostPayDownload(int|string $post_id, float $zibpay_price, array $pay_download, string $pay_title = '', string $pay_doc = '', string $pay_details = '', int $pay_limit = 0, int $pay_cuont = 0): void
        {
            $zibpay_type = 2;
            $pay_modo    = 0;

            $pay_download_data = [];
            foreach ($pay_download as $k => $v)
            {
                $pay_download_data[] = array_merge([
                    'more' => '',
                    'name' => '',
                    'icon' => 'fa fa-download',
                ], $v);
            }

            $posts_zibpay = [
                'pay_type'            => $zibpay_type,
                'pay_limit'           => $pay_limit,
                'pay_modo'            => $pay_modo,
                'pay_price'           => $zibpay_price,
                'vip_1_price'         => ceil($zibpay_price * 0.8),
                'vip_2_price'         => ceil($zibpay_price * 0.6),
                'pay_original_price'  => ceil($zibpay_price * 1.6),
                //                'promotion_tag'       => '<i class="fa fa-fw fa-bolt"></i> 会员购买更便宜',
                'promotion_tag'       => '',
                'points_price'        => '',
                'vip_1_points'        => '',
                'vip_2_points'        => '',
                'pay_rebate_discount' => '',
                'pay_cuont'           => $pay_cuont,
                'pay_extra_hide'      => '',
                'pay_title'           => $pay_title,
                'pay_doc'             => $pay_doc,
                'pay_details'         => $pay_details,

                'pay_gallery'      => '',
                'pay_gallery_show' => '',

                'video_url'          => '',
                'video_pic'          => '',
                'video_title'        => '',
                'video_episode'      => [],
                'video_scale_height' => '0',

                'pay_download' => $pay_download_data,
                'attributes'   => [],
                'demo_link'    => [],
            ];

            $this->setPostPayInfo($post_id, $zibpay_type, $pay_modo, $zibpay_price, 0, $posts_zibpay);
        }

        /**
         * @param int|string $post_id
         * @param float      $zibpay_price
         * @param array      $pay_gallery
         * @param int        $pay_gallery_show
         * @param string     $pay_title
         * @param string     $pay_doc
         * @param string     $pay_details
         * @param int        $pay_limit
         * @param int        $pay_cuont
         *
         * @return void
         */
        public function makePostPayImage(int|string $post_id, float $zibpay_price, array $pay_gallery, int $pay_gallery_show = 0, string $pay_title = '', string $pay_doc = '', string $pay_details = '', int $pay_limit = 0, int $pay_cuont = 0): void
        {
            $zibpay_type = 5;
            $pay_modo    = 0;

            $posts_zibpay = [
                'pay_type'            => $zibpay_type,
                'pay_limit'           => $pay_limit,
                'pay_modo'            => $pay_modo,
                'pay_price'           => $zibpay_price,
                'vip_1_price'         => ceil($zibpay_price * 0.8),
                'vip_2_price'         => ceil($zibpay_price * 0.6),
                'pay_original_price'  => ceil($zibpay_price * 1.6),
                //                'promotion_tag'       => '<i class="fa fa-fw fa-bolt"></i> 会员购买更便宜',
                'promotion_tag'       => '',
                'points_price'        => '',
                'vip_1_points'        => '',
                'vip_2_points'        => '',
                'pay_rebate_discount' => '',
                'pay_cuont'           => $pay_cuont,
                'pay_extra_hide'      => '',
                'pay_title'           => $pay_title,
                'pay_doc'             => $pay_doc,
                'pay_details'         => $pay_details,

                'pay_gallery'      => implode(',', $pay_gallery),
                'pay_gallery_show' => (string)$pay_gallery_show,

                'video_url'          => '',
                'video_pic'          => '',
                'video_title'        => '',
                'video_episode'      => [],
                'video_scale_height' => '0',

                'pay_download' => [],
                'attributes'   => [],
                'demo_link'    => [],
            ];

            $this->setPostPayInfo($post_id, $zibpay_type, $pay_modo, $zibpay_price, 0, $posts_zibpay);
        }

        /**
         * @param int|string $post_id
         * @param float      $zibpay_price
         * @param array      $videos
         * @param string     $pay_title
         * @param string     $pay_doc
         * @param string     $pay_details
         * @param int        $pay_limit
         * @param int        $pay_cuont
         *
         * @return void
         */
        public function makePostPayVideo(int|string $post_id, float $zibpay_price, array $videos, string $pay_title = '', string $pay_doc = '', string $pay_details = '', int $pay_limit = 0, int $pay_cuont = 0): void
        {
            $zibpay_type = 6;
            $pay_modo    = 0;

            $firstVideo = array_shift($videos);

            $video_url   = $firstVideo['url'];
            $video_pic   = $firstVideo['pic'];
            $video_title = $firstVideo['title'];

            $video_episode = [];
            if (count($videos))
            {
                foreach ($videos as $k => $videoInfo)
                {
                    $video_episode[] = array_merge([
                        'title' => $videoInfo['title'] ?? '视频[' . ($k + 2) . ']',
                    ], $videoInfo);
                }
            }

            $posts_zibpay = [
                'pay_type'            => $zibpay_type,
                'pay_limit'           => $pay_limit,
                'pay_modo'            => $pay_modo,
                'pay_price'           => $zibpay_price,
                'vip_1_price'         => ceil($zibpay_price * 0.8),
                'vip_2_price'         => ceil($zibpay_price * 0.6),
                'pay_original_price'  => ceil($zibpay_price * 1.6),
                //                'promotion_tag'       => '<i class="fa fa-fw fa-bolt"></i> 会员购买更便宜',
                'promotion_tag'       => '',
                'points_price'        => '',
                'vip_1_points'        => '',
                'vip_2_points'        => '',
                'pay_rebate_discount' => '',
                'pay_cuont'           => $pay_cuont,
                'pay_extra_hide'      => '',
                'pay_title'           => $pay_title,
                'pay_doc'             => $pay_doc,
                'pay_details'         => $pay_details,

                'pay_gallery'      => '',
                'pay_gallery_show' => '',

                'video_url'          => $video_url,
                'video_pic'          => $video_pic,
                'video_title'        => $video_title,
                'video_episode'      => $video_episode,
                'video_scale_height' => '50',

                'pay_download' => [],
                'attributes'   => [],
                'demo_link'    => [],
            ];

            $this->setPostPayInfo($post_id, $zibpay_type, $pay_modo, $zibpay_price, 0, $posts_zibpay);
        }

        /**
         * 关闭收费
         *
         * @param int|string $post_id
         *
         * @return void
         * @throws \think\db\exception\DbException
         */
        public function makePostPayOff(int|string $post_id): void
        {
            $postmetaTable = $this->getPostmetaTable();

            $postWhere = [
                $postmetaTable->getPostIdField(),
                '=',
                $post_id,
            ];

            $keys = [
                "zibpay_type",
                "zibpay_modo",
                "zibpay_price",
                "zibpay_points_price",
                "posts_zibpay",
            ];

            foreach ($keys as $k => $v)
            {
                $postmetaTable->tableIns()->where(...$postWhere)->where($postmetaTable->getMetaKeyField(), '=', $v)
                    ->delete();
            }
        }

        public function deletePostById(int|array $id): int
        {
            if (is_int($id))
            {
                $ids = [$id];
            }
            else
            {
                $ids = $id;
            }

            $postsTable             = $this->getPostsTable();
            $termRelationshipsTable = $this->getTermRelationshipsTable();
            $postmetaTable          = $this->getPostmetaTable();
            $commentsTable          = $this->getCommentsTable();

            $termRelationshipsTable->tableIns()->where([
                [
                    $termRelationshipsTable->getObjectIdField(),
                    'in',
                    $ids,
                ],
            ])->delete();

            $postmetaTable->tableIns()->where([
                [
                    $postmetaTable->getPostIdField(),
                    'in',
                    $ids,
                ],
            ])->delete();

            $commentsTable->tableIns()->where([
                [
                    $commentsTable->getCommentPostIDField(),
                    'in',
                    $ids,
                ],
            ])->delete();

            return $postsTable->tableIns()->where([
                [
                    $postsTable->getPkField(),
                    'in',
                    $ids,
                ],
            ])->delete();
        }

        public function deletePostByGuid(string|int|array $guid): void
        {
            if (is_int($guid) || is_string($guid))
            {
                $guids = [$guid];
            }
            else
            {
                $guids = $guid;
            }

            $postsTable = $this->getPostsTable();
            $where      = [
                [
                    $postsTable->getGuidField(),
                    'in',
                    $guids,
                ],
            ];

            $postIds = $postsTable->tableIns()->where($where)->column($postsTable->getPkField());

            $this->deletePostById($postIds);
        }

        public function deletePostByKeyword(string $keyword, $includeContent = false, bool $isFullMatch = false): int
        {
            $postsTable = $this->getPostsTable();
            $posts      = $this->searchPostByKeyword($keyword, $includeContent, $isFullMatch);

            $ids = [];
            if (count($posts))
            {
                foreach ($posts as $k => $post)
                {
                    $ids[] = $post[$postsTable->getPkField()];
                }
            }

            return $this->deletePostById($ids);
        }

        public function deleteAllPost(): int
        {
            $postsTable = $this->getPostsTable();

            $ids = $postsTable->tableIns()->where($postsTable->getPostTypeField(), '=', 'post')
                ->whereOr($postsTable->getPostTypeField(), '=', 'revision')->column($postsTable->getPkField());

            return $this->deletePostById($ids);
        }

        public function searchPostByKeyword(string $keyword, $includeContent = false, bool $isFullMatch = false)
        {
            $postsTable = $this->getPostsTable();

            $ins = $postsTable->tableIns();

            $whereTitle   = [];
            $whereContent = [];

            if ($isFullMatch)
            {
                $whereTitle   = [
                    $postsTable->getPostTitleField(),
                    '=',
                    $keyword,
                ];
                $whereContent = [
                    $postsTable->getPostContentField(),
                    '=',
                    $keyword,
                ];
            }
            else
            {
                $whereTitle   = [
                    $postsTable->getPostTitleField(),
                    'like',
                    "%{$keyword}%",
                ];
                $whereContent = [
                    $postsTable->getPostContentField(),
                    'like',
                    "%{$keyword}%",
                ];
            }

            $ins->where(...$whereTitle);

            if ($includeContent)
            {
                $ins->whereOr(...$whereContent);
            }

            return $ins->select();
        }

        public function setPostThumbnail($post_id, $thumbnail_post_id): void
        {
            $this->updatePostmeta($post_id, '_thumbnail_id', $thumbnail_post_id);
        }

        public function updatePostSeo(int $post_id, string $title = '', string $keywords = '', string $description = ''): void
        {
            $this->updatePostmeta($post_id, 'title', $title);
            $this->updatePostmeta($post_id, 'keywords', $keywords);
            $this->updatePostmeta($post_id, 'description', $description);
        }

        public function updatePostmeta($post_id, $key, $value): void
        {
            $postmetaTable = $this->getPostmetaTable();

            $postmetaTable->tableIns()->where([
                [
                    $postmetaTable->getPostIdField(),
                    '=',
                    $post_id,
                ],
                [
                    $postmetaTable->getMetaKeyField(),
                    '=',
                    $key,
                ],
            ])->delete();

            if ($value)
            {
                $postmetaTable->tableIns()->insert([
                    "post_id"    => $post_id,
                    "meta_key"   => $key,
                    "meta_value" => $value,
                ]);
            }
        }


        protected function setPostPayInfo(int|string $post_id, string $zibpay_type, string $zibpay_modo, string $zibpay_price, string $zibpay_points_price, array $posts_zibpay): void
        {
            $postmetaTable = $this->getPostmetaTable();

            $info = [
                "zibpay_type"         => $zibpay_type,
                "zibpay_modo"         => $zibpay_modo,
                "zibpay_price"        => $zibpay_price,
                "zibpay_points_price" => $zibpay_points_price,
                "posts_zibpay"        => serialize($posts_zibpay),
            ];
            $data = [];

            foreach ($info as $k => $v)
            {
                $data[] = [
                    "post_id"    => $post_id,
                    "meta_key"   => $k,
                    "meta_value" => $v,
                ];
            }

            $this->makePostPayOff($post_id);

            $postmetaTable->tableIns()->insertAll($data);
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */
        public function addMedia(string $file, string $targetDir, string $domain = 'http://wp-media'): int|string
        {
            if (!is_file($file))
            {
                return false;
            }

            $fileInfo = pathinfo($file);

            $fileName = hrtime(true) . '.' . $fileInfo['extension'];
            $md5      = md5($fileName);

            // 2025/04-14/16/400612345107640.jpg
            $saveName = date('Y/m-d') . DIRECTORY_SEPARATOR . substr($md5, 0, 2) . DIRECTORY_SEPARATOR . $fileName;

            // http://dev6080/wp-content/uploads/2025/04/09/864335431541458.jpg
            $guid = trim($domain, '\/') . '/wp-content/uploads/' . $saveName;
            // ../data1/2025/04/09/864335431541458.jpg
            $savePath = rtrim($targetDir, '\/') . DIRECTORY_SEPARATOR . $saveName;

            is_dir(dirname($savePath)) or mkdir(dirname($savePath), 0755, true);
            if (!copy($file, $savePath))
            {
                return false;
            }

            $postsTable    = $this->getPostsTable();
            $postmetaTable = $this->getPostmetaTable();

            // 获取当前本地时间
            $local_time = new \DateTime();
            $local_time->setTimezone(new \DateTimeZone('Asia/Shanghai')); // 设置为本地时区（例如上海时区）
            $formatted_post_date = $local_time->format('Y-m-d H:i:s');    // 本地时间，符合 'Y-m-d H:i:s' 格式

            // 获取当前的 GMT 时间
            $gmt_time = new \DateTime();
            $gmt_time->setTimezone(new \DateTimeZone('GMT'));            // 设置为 GMT 时区
            $formatted_post_date_gmt = $gmt_time->format('Y-m-d H:i:s'); // GMT 时间，符合 'Y-m-d H:i:s' 格式

            $mime     = static::getMimeByExt($fileInfo['extension']);
            $filesize = filesize($file);

            $postData = [
                $postsTable->getPostAuthorField()          => 1,
                $postsTable->getPostDateField()            => $formatted_post_date,
                $postsTable->getPostDateGmtField()         => $formatted_post_date_gmt,
                $postsTable->getPostContentField()         => '',
                $postsTable->getPostTitleField()           => $fileInfo['filename'],
                $postsTable->getPostExcerptField()         => '',
                $postsTable->getPostStatusField()          => 'inherit',
                $postsTable->getCommentStatusField()       => 'open',
                $postsTable->getPingStatusField()          => 'closed',
                $postsTable->getPostPasswordField()        => '',
                $postsTable->getPostNameField()            => urlencode($fileInfo['filename']),
                $postsTable->getToPingField()              => '',
                $postsTable->getPingedField()              => '',
                $postsTable->getPostModifiedField()        => $formatted_post_date,
                $postsTable->getPostModifiedGmtField()     => $formatted_post_date_gmt,
                $postsTable->getPostContentFilteredField() => '',
                $postsTable->getPostParentField()          => 0,
                $postsTable->getGuidField()                => $guid,
                $postsTable->getMenuOrderField()           => 0,
                $postsTable->getPostTypeField()            => 'attachment',
                $postsTable->getPostMimeTypeField()        => $mime,
                $postsTable->getCommentCountField()        => 0,
            ];
            $postId   = $postsTable->tableIns()->insertGetId($postData);

            if (str_starts_with($mime, 'image'))
            {
                $imageInfo = getimagesize($file);

                $meta = [
                    "width"      => $imageInfo[0],
                    "height"     => $imageInfo[1],
                    "file"       => $saveName,
                    "filesize"   => $filesize,
                    "sizes"      => [
                        "medium"    => [
                            "file"      => $fileName,
                            "width"     => $imageInfo[0],
                            "height"    => $imageInfo[1],
                            "mime-type" => $mime,
                            "filesize"  => $filesize,
                        ],
                        "thumbnail" => [
                            "file"      => $fileName,
                            "width"     => $imageInfo[0],
                            "height"    => $imageInfo[1],
                            "mime-type" => $mime,
                            "filesize"  => $filesize,
                        ],
                    ],
                    "image_meta" => [
                        "aperture"          => "0",
                        "credit"            => "",
                        "camera"            => "",
                        "caption"           => "",
                        "created_timestamp" => "0",
                        "copyright"         => "",
                        "focal_length"      => "0",
                        "iso"               => "0",
                        "shutter_speed"     => "0",
                        "title"             => "",
                        "orientation"       => "0",
                        "keywords"          => [],
                    ],
                ];
            }
            else
            {
                $meta = [
                    "filesize" => $filesize,
                ];
            }

            $postMeta = [
                [
                    $postmetaTable->getPostIdField()    => $postId,
                    $postmetaTable->getMetaKeyField()   => '_wp_attached_file',
                    $postmetaTable->getMetaValueField() => $saveName,
                ],
                [
                    $postmetaTable->getPostIdField()    => $postId,
                    $postmetaTable->getMetaKeyField()   => '_wp_attachment_metadata',
                    $postmetaTable->getMetaValueField() => serialize($meta),
                ],
            ];

            $postmetaTable->tableIns()->insertAll($postMeta);

            return $postId;
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */
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

        public function purgePostMeta(): void
        {
            $termTaxonomyTable      = $this->getTermTaxonomyTable();
            $termTable              = $this->getTermsTable();
            $termmetaTable          = $this->getTermmetaTable();
            $termRelationshipsTable = $this->getTermRelationshipsTable();
            $postsTable             = $this->getPostsTable();
            $postmetaTable          = $this->getPostmetaTable();

            $data = $postsTable->tableIns()->column($postsTable->getPkField());

            $postmetaTable->tableIns()->where([
                [
                    $postmetaTable->getPostIdField(),
                    'not in',
                    $data,
                ],
            ])->delete();
        }

        public function updateTagsCount(): void
        {
            $termTaxonomyTable      = $this->getTermTaxonomyTable();
            $termTable              = $this->getTermsTable();
            $termmetaTable          = $this->getTermmetaTable();
            $termRelationshipsTable = $this->getTermRelationshipsTable();
            $postsTable             = $this->getPostsTable();
            $postmetaTable          = $this->getPostmetaTable();

            $tagTermTaxonomyIds = $termTaxonomyTable->tableIns()->where([
                [
                    $termTaxonomyTable->getTaxonomyField(),
                    '=',
                    'post_tag',
                ],
            ])->column($termTaxonomyTable->getTermTaxonomyIdField());

            $postIds = $termRelationshipsTable->tableIns()->where([
                [
                    $termRelationshipsTable->getTermTaxonomyIdField(),
                    'in',
                    $tagTermTaxonomyIds,
                ],
            ])->select();

            $counts = [];
            foreach ($postIds as $k => $v)
            {
                if (!isset($counts[$v[$termRelationshipsTable->getTermTaxonomyIdField()]]))
                {
                    $counts[$v[$termRelationshipsTable->getTermTaxonomyIdField()]] = 0;
                }

                $counts[$v[$termRelationshipsTable->getTermTaxonomyIdField()]]++;
            }

            foreach ($counts as $k => $v)
            {
                $termTaxonomyTable->tableIns()->where([
                    [
                        $termTaxonomyTable->getTermTaxonomyIdField(),
                        '=',
                        $k,
                    ],
                ])->update([
                    $termTaxonomyTable->getCountField() => $v,
                ]);
            }
        }


        /*
         *
         * ------------------------------------------------------
         *
         * */

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

        public function replaceAll(array $replacement): void
        {
            uksort($replacement, function($a, $b) {
                return mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8');  // 按照字符长度降序排列
            });

            foreach ($replacement as $name => $value)
            {
                $this->getMysqlClient()->logInfo('【' . $name . '】 ----> 【' . $value . '】');

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
                        }
                        else
                        {
                            $tMap = [];

                            $tMap[strtr($form, ["/" => "\/"])] = strtr($to, ["/" => "\/"]);

                            $json  = json_encode($value, 256);
                            $json  = strtr($json, $tMap);
                            $value = json_decode($json, 1);

                            $newOptions[$item['option_name']] = serialize($value);
                        }
                    }
                    //序列化过，值不是数组，不知道什么类型，先不替换
                    else
                    {

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

            $result   = $postmetaTable->tableIns()->select();
            $newValue = [];

            // 遍历每个选项
            // 将序列化过的值反序列化后，修改过来，再序列化后更新进去
            foreach ($result as $item)
            {
                //值是被序列化过的选项
                if (preg_match('/^[a-z]:\d+/i', $item['meta_value']))
                {
                    $value = unserialize($item['meta_value']);

                    //序列化过，值是数组
                    if (preg_match('/^a:\d+/i', $item['meta_value']))
                    {
                        //反序列化失败
                        if (!is_array($value))
                        {
                            $newValue[$item['meta_id']] = $item['meta_value'];

                            $this->getMysqlClient()->logInfo('反序列化出错：' . $item['meta_key']);
                        }
                        else
                        {
                            $tMap = [];

                            $tMap[strtr($form, ["/" => "\/"])] = strtr($to, ["/" => "\/"]);

                            $json  = json_encode($value, 256);
                            $json  = strtr($json, $tMap);
                            $value = json_decode($json, 1);

                            $newValue[$item['meta_id']] = serialize($value);
                        }
                    }
                    //序列化过，值不是数组，不知道什么类型，先不替换
                    else
                    {

                    }
                }
                //值没有被序列化过
                else
                {
                    $newValue[$item['meta_id']] = strtr($item['meta_value'], [$form => $to]);
                }
            }

            foreach ($newValue as $meta_id => $meta_value)
            {
                $this->getMysqlClient()->logInfo('meta_id: ' . $meta_id);

                $postmetaTable->tableIns()->where('meta_id', $meta_id)->update(['meta_value' => $meta_value]);
            }
        }

        public function updateAllPostView($viewsMin = 1, $viewsMax = 20, $force = false): void
        {
            //----------------------------------------------------------------
            $postsTable    = $this->getPostsTable();
            $postmetaTable = $this->getPostmetaTable();

            //所有文章的id
            $postIds = $postsTable->tableIns()->where([
                [
                    $postsTable->getGuidField(),
                    'regexp',
                    '^[0-9]{18,20}$',
                ],
            ])->order($postsTable->getPkField(), 'asc')->column($postsTable->getPkField());
            $postIds = array_reverse($postIds);

            //----------------------------------------------------------------

            //构造每个文章的views
            $viewsArray = [];

            if (!$force)
            {
                foreach ($postIds as $k => $id)
                {
                    //如果之前不存在，就写入更新
                    $isExists = $postmetaTable->tableIns()->where([
                        [
                            $postmetaTable->getMetaKeyField(),
                            '=',
                            'views',
                        ],
                        [
                            $postmetaTable->getPostIdField(),
                            '=',
                            $id,
                        ],
                    ])->find();

                    if (!$isExists)
                    {
                        $viewsArray[] = [
                            $postmetaTable->getPostIdField()    => $id,
                            $postmetaTable->getMetaKeyField()   => 'views',
                            $postmetaTable->getMetaValueField() => round(bcsqrt($k)) * rand($viewsMin, $viewsMax),
                        ];

                        echo implode('', [
                            $id,
                            '-更新【none-force】',
                            PHP_EOL,
                        ]);
                    }
                    else
                    {
                        echo implode('', [
                            $id,
                            '-没更新【none-force】',
                            PHP_EOL,
                        ]);
                    }
                }

            }
            else
            {
                foreach ($postIds as $k => $id)
                {
                    $viewsArray[] = [
                        $postmetaTable->getPostIdField()    => $id,
                        $postmetaTable->getMetaKeyField()   => 'views',
                        $postmetaTable->getMetaValueField() => round(bcsqrt($k)) * rand($viewsMin, $viewsMax),
                    ];

                    echo implode('', [
                        $id,
                        '-更新【force】',
                        PHP_EOL,
                    ]);
                }

                $postmetaTable->tableIns()->where([
                    [
                        $postmetaTable->getMetaKeyField(),
                        '=',
                        'views',
                    ],
                ])->delete();
            }
            $postmetaTable->tableIns()->insertAll($viewsArray);
        }

//        $begin = '2024-1-5';
//        $end   = date('Y-m-d');
//        $times = 222;
        public function updateAllPostPublishTime($begin, $end, $times, $force = false): void
        {
            $postsTable = $this->getPostsTable();

            $ids = $postsTable->tableIns()->field(implode(',', [$postsTable->getPkField()]))
                ->order($postsTable->getPkField(), 'asc')->select();

            $totalArticles = count($ids);

            $updateTimes  = static::updateArticleCreateTime($begin, $end, $times, $totalArticles);
            $itemPerTimes = 0;

            $cishu      = 0;
            $incSeconds = 0;
            $data       = $updateTimes[$cishu];

            foreach ($ids as $k => $id)
            {
                if (!$itemPerTimes)
                {
                    //每次更新的每个文章都加几秒
                    $incSeconds = 0;
                    $data       = $updateTimes[$cishu];
                    //每次更新了几条
                    $itemPerTimes = $data['articles'];
                    $cishu++;
                }

                $date_ = date('Y-m-d H:i:s', strtotime($data['timestamp']) + $incSeconds);

                $date    = $date_;
                $dateGtm = date('Y-m-d H:i:s', strtotime($date_) - 3600 * 8);

                $dataToInsert = [
                    $postsTable->getPostDateField()        => $date,
                    $postsTable->getPostDateGmtField()     => $dateGtm,
                    $postsTable->getPostModifiedField()    => $date,
                    $postsTable->getPostModifiedGmtField() => $dateGtm,
                ];

                if ($force)
                {
                    $c = $postsTable->tableIns()->where($postsTable->getPkField(), '=', $id['ID'])
                        ->update($dataToInsert);
                }
                else
                {
                    $c = $postsTable->tableIns()->where($postsTable->getPkField(), '=', $id['ID'])
                        ->whereTime($postsTable->getPostDateField(), '-1 month')->update($dataToInsert);
                }

                echo implode('', [
                    $id['ID'],
                    '-',
                    $c ? '已更新: '.$date_ : '不更',
                    PHP_EOL,
                ]);

                $itemPerTimes--;

                //每个文章向后递加这么多秒数
                $incSeconds += rand(1, 10);
            }
        }

        /*
         *
         * ------------------------------------------------------
         *
         * */

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

        private function initRedis(): void
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

        }

        private static function updateArticleCreateTime($begin, $end, $times, $totalArticles): array
        {
            if ($totalArticles < 1)
            {
                return [];
            }

            if ($times > $totalArticles)
            {
                $times = $totalArticles;
            }

            // 将开始时间和结束时间转为时间戳
            $startTimestamp = strtotime($begin);
            $endTimestamp   = strtotime($end);

            // 用于存储更新的时间点和每个时间点更新的文章数
            $updateTimes = [];

            //平均间隔秒数
            $avgInterval = (int)(($endTimestamp - $startTimestamp) / ($times - 1));

            $count = static::divide($totalArticles, $times);

            $currentTimestamp = $startTimestamp;

            foreach ($count as $k => $v)
            {
                $updateTimes[]    = [
                    'timestamp' => date('Y-m-d H:i:s', $currentTimestamp - rand(1000, 7000)),
                    'articles'  => $v,
                ];
                $currentTimestamp += $avgInterval;
            }

            return $updateTimes;
        }

        private static function divide($total, $parts): array
        {
            // 计算基本值和余数
            $base      = intdiv($total, $parts); // 基本数
            $remainder = $total % $parts;        // 剩余数

            // 创建数组，初始值为基本数
            $result = array_fill(0, $parts, $base);

            // 将剩余数均匀分配到数组中的前 $remainder 个元素
            for ($i = 0; $i < $remainder; $i++)
            {
                $result[$i]++;
            }

            return $result;
        }

        private static function getMimeByExt($ext): string
        {
            $map = [
                'pdf'   => 'application/pdf',
                'doc'   => 'application/msword',
                'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls'   => 'application/vnd.ms-excel',
                'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt'   => 'application/vnd.ms-powerpoint',
                'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'rtf'   => 'application/rtf',
                'txt'   => 'text/plain',
                'csv'   => 'text/csv',
                'xml'   => 'application/xml',
                'json'  => 'application/json',
                'odt'   => 'application/vnd.oasis.opendocument.text',
                'ods'   => 'application/vnd.oasis.opendocument.spreadsheet',
                'odp'   => 'application/vnd.oasis.opendocument.presentation',
                'zip'   => 'application/zip',
                'gz'    => 'application/gzip',
                'tar'   => 'application/x-tar',
                'bz2'   => 'application/x-bzip2',
                'rar'   => 'application/vnd.rar',
                '7z'    => 'application/x-7z-compressed',
                'mp4'   => 'video/mp4',
                'avi'   => 'video/x-msvideo',
                'flv'   => 'video/x-flv',
                'ogv'   => 'video/ogg',
                'webm'  => 'video/webm',
                'mpg'   => 'video/mpeg',
                'mov'   => 'video/quicktime',
                'wmv'   => 'video/x-ms-wmv',
                'mkv'   => 'video/x-matroska',
                'rmvb'  => 'application/vnd.rn-realmedia-vbr',
                '3gp'   => 'video/3gpp',
                '3g2'   => 'video/3gpp2',
                'asf'   => 'video/x-ms-asf',
                'jpg'   => 'image/jpeg',
                'png'   => 'image/png',
                'gif'   => 'image/gif',
                'svg'   => 'image/svg+xml',
                'webp'  => 'image/webp',
                'bmp'   => 'image/bmp',
                'tiff'  => 'image/tiff',
                'heif'  => 'image/heif',
                'heic'  => 'image/heic',
                'jfif'  => 'image/jpeg',
                'psd'   => 'image/vnd.adobe.photoshop',
                'ico'   => 'image/x-icon',
                'ras'   => 'image/x-cmu-raster',
                'ppm'   => 'image/x-portable-pixmap',
                'mp3'   => 'audio/mpeg',
                'wav'   => 'audio/wav',
                'ogg'   => 'audio/ogg',
                'mid'   => 'audio/midi',
                'aac'   => 'audio/aac',
                'wma'   => 'audio/x-ms-wma',
                'flac'  => 'audio/flac',
                'ra'    => 'audio/vnd.rn-realaudio',
                'opus'  => 'audio/opus',
                'aiff'  => 'audio/aiff',
                'm4a'   => 'audio/mp4',
                'html'  => 'text/html',
                'css'   => 'text/css',
                'js'    => 'application/javascript',
                'md'    => 'text/markdown',
                'sh'    => 'application/x-sh',
                'bin'   => 'application/octet-stream',
                'swf'   => 'application/x-shockwave-flash',
                'apk'   => 'application/vnd.android.package-archive',
                'epub'  => 'application/epub+zip',
                'ttf'   => 'font/ttf',
                'otf'   => 'font/otf',
                'woff'  => 'font/woff',
                'woff2' => 'font/woff2',
                'cab'   => 'application/vnd.ms-cab-compressed',
                'qtl'   => 'application/x-qtiplot',
                'exe'   => 'application/x-msdownload',
            ];

            return $map[$ext] ?? 'unknow';
        }
    }