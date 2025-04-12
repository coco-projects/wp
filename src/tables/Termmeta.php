<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Termmeta extends TableAbstract
    {
        public string $comment = '术语元数据';

        public array $fieldsSqlMap = [
            "meta_id"    => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "term_id"    => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "meta_key"   => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,",
            "meta_value" => "`__FIELD__NAME__` longtext COLLATE utf8mb4_unicode_520_ci,",
        ];

        protected array $indexSentence = [
            "term_id"  => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
            "meta_key" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
        ];

        public function setMetaIdField(string $value): static
        {
            $this->setFeildName('meta_id', $value);

            return $this;
        }

        public function getMetaIdField(): string
        {
            return $this->getFieldName('meta_id');
        }

        public function setTermIdField(string $value): static
        {
            $this->setFeildName('term_id', $value);

            return $this;
        }

        public function getTermIdField(): string
        {
            return $this->getFieldName('term_id');
        }

        public function setMetaKeyField(string $value): static
        {
            $this->setFeildName('meta_key', $value);

            return $this;
        }

        public function getMetaKeyField(): string
        {
            return $this->getFieldName('meta_key');
        }

        public function setMetaValueField(string $value): static
        {
            $this->setFeildName('meta_value', $value);

            return $this;
        }

        public function getMetaValueField(): string
        {
            return $this->getFieldName('meta_value');
        }

    }