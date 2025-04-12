<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Terms extends TableAbstract
    {
        public string $comment = '术语';

        public array $fieldsSqlMap = [
            "term_id"    => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "name"       => "`__FIELD__NAME__` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "slug"       => "`__FIELD__NAME__` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "term_group" => "`__FIELD__NAME__` bigint(10) NOT NULL DEFAULT '0',",
        ];

        protected array $indexSentence = [
            "slug" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
            "name" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
        ];

        public function setTermIdField(string $value): static
        {
            $this->setFeildName('term_id', $value);

            return $this;
        }

        public function getTermIdField(): string
        {
            return $this->getFieldName('term_id');
        }

        public function setNameField(string $value): static
        {
            $this->setFeildName('name', $value);

            return $this;
        }

        public function getNameField(): string
        {
            return $this->getFieldName('name');
        }

        public function setSlugField(string $value): static
        {
            $this->setFeildName('slug', $value);

            return $this;
        }

        public function getSlugField(): string
        {
            return $this->getFieldName('slug');
        }

        public function setTermGroupField(string $value): static
        {
            $this->setFeildName('term_group', $value);

            return $this;
        }

        public function getTermGroupField(): string
        {
            return $this->getFieldName('term_group');
        }

    }