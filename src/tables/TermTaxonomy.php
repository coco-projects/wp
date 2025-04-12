<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class TermTaxonomy extends TableAbstract
    {
        public string $comment = '术语分类';

        public array $fieldsSqlMap = [
            "term_taxonomy_id" => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "term_id"          => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "taxonomy"         => "`__FIELD__NAME__` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "description"      => "`__FIELD__NAME__` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "parent"           => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "count"            => "`__FIELD__NAME__` bigint(20) NOT NULL DEFAULT '0',",
        ];

        protected array $indexSentence = [
            "taxonomy" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
        ];

        public function setTermTaxonomyIdField(string $value): static
        {
            $this->setFeildName('term_taxonomy_id', $value);

            return $this;
        }

        public function getTermTaxonomyIdField(): string
        {
            return $this->getFieldName('term_taxonomy_id');
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

        public function setTaxonomyField(string $value): static
        {
            $this->setFeildName('taxonomy', $value);

            return $this;
        }

        public function getTaxonomyField(): string
        {
            return $this->getFieldName('taxonomy');
        }

        public function setDescriptionField(string $value): static
        {
            $this->setFeildName('description', $value);

            return $this;
        }

        public function getDescriptionField(): string
        {
            return $this->getFieldName('description');
        }

        public function setParentField(string $value): static
        {
            $this->setFeildName('parent', $value);

            return $this;
        }

        public function getParentField(): string
        {
            return $this->getFieldName('parent');
        }

        public function setCountField(string $value): static
        {
            $this->setFeildName('count', $value);

            return $this;
        }

        public function getCountField(): string
        {
            return $this->getFieldName('count');
        }


    }