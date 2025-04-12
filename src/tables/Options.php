<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Options extends TableAbstract
    {
        public string $comment = '配置表';

        public array $fieldsSqlMap = [
            "option_id"    => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "option_name"  => "`__FIELD__NAME__` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "option_value" => "`__FIELD__NAME__` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "autoload"     => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',",
        ];

        protected array $indexSentence = [
            "autoload" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
        ];

        public function setOptionIdField(string $value): static
        {
            $this->setFeildName('option_id', $value);

            return $this;
        }

        public function getOptionIdField(): string
        {
            return $this->getFieldName('option_id');
        }

        public function setOptionNameField(string $value): static
        {
            $this->setFeildName('option_name', $value);

            return $this;
        }

        public function getOptionNameField(): string
        {
            return $this->getFieldName('option_name');
        }

        public function setOptionValueField(string $value): static
        {
            $this->setFeildName('option_value', $value);

            return $this;
        }

        public function getOptionValueField(): string
        {
            return $this->getFieldName('option_value');
        }

        public function setAutoloadField(string $value): static
        {
            $this->setFeildName('autoload', $value);

            return $this;
        }

        public function getAutoloadField(): string
        {
            return $this->getFieldName('autoload');
        }

    }