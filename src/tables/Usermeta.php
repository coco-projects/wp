<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Usermeta extends TableAbstract
    {
        public string $comment = '用户元数据';

        public array $fieldsSqlMap = [
            "umeta_id"   => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "user_id"    => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "meta_key"   => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,",
            "meta_value" => "`__FIELD__NAME__` longtext COLLATE utf8mb4_unicode_520_ci,",
        ];

        protected array $indexSentence = [
            "user_id"  => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
            "meta_key" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
        ];

        public function setUmetaIdField(string $value): static
        {
            $this->setFeildName('umeta_id', $value);

            return $this;
        }

        public function getUmetaIdField(): string
        {
            return $this->getFieldName('umeta_id');
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