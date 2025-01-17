<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class TermRelationships extends TableAbstract
    {
        public string $comment = '术语关联';

        public array $fieldsSqlMap = [
            "object_id"        => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "term_taxonomy_id" => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '0',",
            "term_order"       => "`__FIELD__NAME__` int(11) NOT NULL DEFAULT '0',",
        ];

        protected array $indexSentence = [
            "term_taxonomy_id" => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
        ];

        public function setObjectIdField(string $value): static
        {
            $this->setFeildName('object_id', $value);

            return $this;
        }

        public function getObjectIdField(): string
        {
            return $this->getFieldName('object_id');
        }

        public function setTermTaxonomyIdField(string $value): static
        {
            $this->setFeildName('term_taxonomy_id', $value);

            return $this;
        }

        public function getTermTaxonomyIdField(): string
        {
            return $this->getFieldName('term_taxonomy_id');
        }

        public function setTermOrderField(string $value): static
        {
            $this->setFeildName('term_order', $value);

            return $this;
        }

        public function getTermOrderField(): string
        {
            return $this->getFieldName('term_order');
        }


    }