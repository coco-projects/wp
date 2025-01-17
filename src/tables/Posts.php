<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Posts extends TableAbstract
    {
        public string $comment = '文章';

        public array $fieldsSqlMap = [
            "ID"                    => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "post_author"           => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "post_date"             => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "post_date_gmt"         => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "post_content"          => "`__FIELD__NAME__` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "post_title"            => "`__FIELD__NAME__` text COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "post_excerpt"          => "`__FIELD__NAME__` text COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "post_status"           => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',",
            "comment_status"        => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',",
            "ping_status"           => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',",
            "post_password"         => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "post_name"             => "`__FIELD__NAME__` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "to_ping"               => "`__FIELD__NAME__` text COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "pinged"                => "`__FIELD__NAME__` text COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "post_modified"         => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "post_modified_gmt"     => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "post_content_filtered" => "`__FIELD__NAME__` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "post_parent"           => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "guid"                  => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "menu_order"            => "`__FIELD__NAME__` int(11) NOT NULL DEFAULT '0',",
            "post_type"             => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',",
            "post_mime_type"        => "`__FIELD__NAME__` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "comment_count"         => "`__FIELD__NAME__` bigint(20) NOT NULL DEFAULT '0',",
        ];

        protected array $indexSentence = [
            "post_name"                          => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "post_type,post_status,post_date,ID" => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "post_parent"                        => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "post_author"                        => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
        ];

        public function setIDField(string $value): static
        {
            $this->setFeildName('ID', $value);

            return $this;
        }

        public function getIDField(): string
        {
            return $this->getFieldName('ID');
        }

        public function setPostAuthorField(string $value): static
        {
            $this->setFeildName('post_author', $value);

            return $this;
        }

        public function getPostAuthorField(): string
        {
            return $this->getFieldName('post_author');
        }

        public function setPostDateField(string $value): static
        {
            $this->setFeildName('post_date', $value);

            return $this;
        }

        public function getPostDateField(): string
        {
            return $this->getFieldName('post_date');
        }

        public function setPostDateGmtField(string $value): static
        {
            $this->setFeildName('post_date_gmt', $value);

            return $this;
        }

        public function getPostDateGmtField(): string
        {
            return $this->getFieldName('post_date_gmt');
        }

        public function setPostContentField(string $value): static
        {
            $this->setFeildName('post_content', $value);

            return $this;
        }

        public function getPostContentField(): string
        {
            return $this->getFieldName('post_content');
        }

        public function setPostTitleField(string $value): static
        {
            $this->setFeildName('post_title', $value);

            return $this;
        }

        public function getPostTitleField(): string
        {
            return $this->getFieldName('post_title');
        }

        public function setPostExcerptField(string $value): static
        {
            $this->setFeildName('post_excerpt', $value);

            return $this;
        }

        public function getPostExcerptField(): string
        {
            return $this->getFieldName('post_excerpt');
        }

        public function setPostStatusField(string $value): static
        {
            $this->setFeildName('post_status', $value);

            return $this;
        }

        public function getPostStatusField(): string
        {
            return $this->getFieldName('post_status');
        }

        public function setCommentStatusField(string $value): static
        {
            $this->setFeildName('comment_status', $value);

            return $this;
        }

        public function getCommentStatusField(): string
        {
            return $this->getFieldName('comment_status');
        }

        public function setPingStatusField(string $value): static
        {
            $this->setFeildName('ping_status', $value);

            return $this;
        }

        public function getPingStatusField(): string
        {
            return $this->getFieldName('ping_status');
        }

        public function setPostPasswordField(string $value): static
        {
            $this->setFeildName('post_password', $value);

            return $this;
        }

        public function getPostPasswordField(): string
        {
            return $this->getFieldName('post_password');
        }

        public function setPostNameField(string $value): static
        {
            $this->setFeildName('post_name', $value);

            return $this;
        }

        public function getPostNameField(): string
        {
            return $this->getFieldName('post_name');
        }

        public function setToPingField(string $value): static
        {
            $this->setFeildName('to_ping', $value);

            return $this;
        }

        public function getToPingField(): string
        {
            return $this->getFieldName('to_ping');
        }

        public function setPingedField(string $value): static
        {
            $this->setFeildName('pinged', $value);

            return $this;
        }

        public function getPingedField(): string
        {
            return $this->getFieldName('pinged');
        }

        public function setPostModifiedField(string $value): static
        {
            $this->setFeildName('post_modified', $value);

            return $this;
        }

        public function getPostModifiedField(): string
        {
            return $this->getFieldName('post_modified');
        }

        public function setPostModifiedGmtField(string $value): static
        {
            $this->setFeildName('post_modified_gmt', $value);

            return $this;
        }

        public function getPostModifiedGmtField(): string
        {
            return $this->getFieldName('post_modified_gmt');
        }

        public function setPostContentFilteredField(string $value): static
        {
            $this->setFeildName('post_content_filtered', $value);

            return $this;
        }

        public function getPostContentFilteredField(): string
        {
            return $this->getFieldName('post_content_filtered');
        }

        public function setPostParentField(string $value): static
        {
            $this->setFeildName('post_parent', $value);

            return $this;
        }

        public function getPostParentField(): string
        {
            return $this->getFieldName('post_parent');
        }

        public function setGuidField(string $value): static
        {
            $this->setFeildName('guid', $value);

            return $this;
        }

        public function getGuidField(): string
        {
            return $this->getFieldName('guid');
        }

        public function setMenuOrderField(string $value): static
        {
            $this->setFeildName('menu_order', $value);

            return $this;
        }

        public function getMenuOrderField(): string
        {
            return $this->getFieldName('menu_order');
        }

        public function setPostTypeField(string $value): static
        {
            $this->setFeildName('post_type', $value);

            return $this;
        }

        public function getPostTypeField(): string
        {
            return $this->getFieldName('post_type');
        }

        public function setPostMimeTypeField(string $value): static
        {
            $this->setFeildName('post_mime_type', $value);

            return $this;
        }

        public function getPostMimeTypeField(): string
        {
            return $this->getFieldName('post_mime_type');
        }

        public function setCommentCountField(string $value): static
        {
            $this->setFeildName('comment_count', $value);

            return $this;
        }

        public function getCommentCountField(): string
        {
            return $this->getFieldName('comment_count');
        }

    }