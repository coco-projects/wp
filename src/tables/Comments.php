<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Comments extends TableAbstract
    {
        public string $comment = '注释';

        public array $fieldsSqlMap = [
            "comment_ID"           => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "comment_post_ID"      => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "comment_author"       => "`__FIELD__NAME__` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "comment_author_email" => "`__FIELD__NAME__` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "comment_author_url"   => "`__FIELD__NAME__` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "comment_author_IP"    => "`__FIELD__NAME__` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "comment_date"         => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "comment_date_gmt"     => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "comment_content"      => "`__FIELD__NAME__` text COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "comment_karma"        => "`__FIELD__NAME__` int(11) NOT NULL DEFAULT '0',",
            "comment_approved"     => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',",
            "comment_agent"        => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "comment_type"         => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'comment',",
            "comment_parent"       => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "user_id"              => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
        ];

        protected array $indexSentence = [
            "comment_post_ID"                   => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "comment_approved,comment_date_gmt" => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "comment_date_gmt"                  => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "comment_parent"                    => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
            "comment_author_email"              => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
        ];


        public function setCommentIDField(string $value): static
        {
            $this->setFeildName('comment_ID', $value);

            return $this;
        }

        public function getCommentIDField(): string
        {
            return $this->getFieldName('comment_ID');
        }

        public function setCommentPostIDField(string $value): static
        {
            $this->setFeildName('comment_post_ID', $value);

            return $this;
        }

        public function getCommentPostIDField(): string
        {
            return $this->getFieldName('comment_post_ID');
        }

        public function setCommentAuthorField(string $value): static
        {
            $this->setFeildName('comment_author', $value);

            return $this;
        }

        public function getCommentAuthorField(): string
        {
            return $this->getFieldName('comment_author');
        }

        public function setCommentAuthorEmailField(string $value): static
        {
            $this->setFeildName('comment_author_email', $value);

            return $this;
        }

        public function getCommentAuthorEmailField(): string
        {
            return $this->getFieldName('comment_author_email');
        }

        public function setCommentAuthorUrlField(string $value): static
        {
            $this->setFeildName('comment_author_url', $value);

            return $this;
        }

        public function getCommentAuthorUrlField(): string
        {
            return $this->getFieldName('comment_author_url');
        }

        public function setCommentAuthorIPField(string $value): static
        {
            $this->setFeildName('comment_author_IP', $value);

            return $this;
        }

        public function getCommentAuthorIPField(): string
        {
            return $this->getFieldName('comment_author_IP');
        }

        public function setCommentDateField(string $value): static
        {
            $this->setFeildName('comment_date', $value);

            return $this;
        }

        public function getCommentDateField(): string
        {
            return $this->getFieldName('comment_date');
        }

        public function setCommentDateGmtField(string $value): static
        {
            $this->setFeildName('comment_date_gmt', $value);

            return $this;
        }

        public function getCommentDateGmtField(): string
        {
            return $this->getFieldName('comment_date_gmt');
        }

        public function setCommentContentField(string $value): static
        {
            $this->setFeildName('comment_content', $value);

            return $this;
        }

        public function getCommentContentField(): string
        {
            return $this->getFieldName('comment_content');
        }

        public function setCommentKarmaField(string $value): static
        {
            $this->setFeildName('comment_karma', $value);

            return $this;
        }

        public function getCommentKarmaField(): string
        {
            return $this->getFieldName('comment_karma');
        }

        public function setCommentApprovedField(string $value): static
        {
            $this->setFeildName('comment_approved', $value);

            return $this;
        }

        public function getCommentApprovedField(): string
        {
            return $this->getFieldName('comment_approved');
        }

        public function setCommentAgentField(string $value): static
        {
            $this->setFeildName('comment_agent', $value);

            return $this;
        }

        public function getCommentAgentField(): string
        {
            return $this->getFieldName('comment_agent');
        }

        public function setCommentTypeField(string $value): static
        {
            $this->setFeildName('comment_type', $value);

            return $this;
        }

        public function getCommentTypeField(): string
        {
            return $this->getFieldName('comment_type');
        }

        public function setCommentParentField(string $value): static
        {
            $this->setFeildName('comment_parent', $value);

            return $this;
        }

        public function getCommentParentField(): string
        {
            return $this->getFieldName('comment_parent');
        }

        public function setUserIdField(string $value): static
        {
            $this->setFeildName('user_id', $value);

            return $this;
        }

        public function getUserIdField(): string
        {
            return $this->getFieldName('user_id');
        }


    }