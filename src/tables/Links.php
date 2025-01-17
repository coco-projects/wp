<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Links extends TableAbstract
    {
        public string $comment = '友情链接';

        public array $fieldsSqlMap = [
            "link_id"          => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "link_url"         => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "link_name"        => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "link_image"       => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "link_target"      => "`__FIELD__NAME__` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "link_description" => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "link_visible"     => "`__FIELD__NAME__` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',",
            "link_owner"       => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL DEFAULT '1',",
            "link_rating"      => "`__FIELD__NAME__` int(11) NOT NULL DEFAULT '0',",
            "link_updated"     => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "link_rel"         => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "link_notes"       => "`__FIELD__NAME__` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,",
            "link_rss"         => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
        ];

        protected array $indexSentence = [
            "link_visible" => "KEY `__FIELD__NAME___index` (`__FIELD__NAME__`),",
        ];


        public function setLinkIdField(string $value): static
        {
            $this->setFeildName('link_id', $value);

            return $this;
        }

        public function getLinkIdField(): string
        {
            return $this->getFieldName('link_id');
        }

        public function setLinkUrlField(string $value): static
        {
            $this->setFeildName('link_url', $value);

            return $this;
        }

        public function getLinkUrlField(): string
        {
            return $this->getFieldName('link_url');
        }

        public function setLinkNameField(string $value): static
        {
            $this->setFeildName('link_name', $value);

            return $this;
        }

        public function getLinkNameField(): string
        {
            return $this->getFieldName('link_name');
        }

        public function setLinkImageField(string $value): static
        {
            $this->setFeildName('link_image', $value);

            return $this;
        }

        public function getLinkImageField(): string
        {
            return $this->getFieldName('link_image');
        }

        public function setLinkTargetField(string $value): static
        {
            $this->setFeildName('link_target', $value);

            return $this;
        }

        public function getLinkTargetField(): string
        {
            return $this->getFieldName('link_target');
        }

        public function setLinkDescriptionField(string $value): static
        {
            $this->setFeildName('link_description', $value);

            return $this;
        }

        public function getLinkDescriptionField(): string
        {
            return $this->getFieldName('link_description');
        }

        public function setLinkVisibleField(string $value): static
        {
            $this->setFeildName('link_visible', $value);

            return $this;
        }

        public function getLinkVisibleField(): string
        {
            return $this->getFieldName('link_visible');
        }

        public function setLinkOwnerField(string $value): static
        {
            $this->setFeildName('link_owner', $value);

            return $this;
        }

        public function getLinkOwnerField(): string
        {
            return $this->getFieldName('link_owner');
        }

        public function setLinkRatingField(string $value): static
        {
            $this->setFeildName('link_rating', $value);

            return $this;
        }

        public function getLinkRatingField(): string
        {
            return $this->getFieldName('link_rating');
        }

        public function setLinkUpdatedField(string $value): static
        {
            $this->setFeildName('link_updated', $value);

            return $this;
        }

        public function getLinkUpdatedField(): string
        {
            return $this->getFieldName('link_updated');
        }

        public function setLinkRelField(string $value): static
        {
            $this->setFeildName('link_rel', $value);

            return $this;
        }

        public function getLinkRelField(): string
        {
            return $this->getFieldName('link_rel');
        }

        public function setLinkNotesField(string $value): static
        {
            $this->setFeildName('link_notes', $value);

            return $this;
        }

        public function getLinkNotesField(): string
        {
            return $this->getFieldName('link_notes');
        }

        public function setLinkRssField(string $value): static
        {
            $this->setFeildName('link_rss', $value);

            return $this;
        }

        public function getLinkRssField(): string
        {
            return $this->getFieldName('link_rss');
        }

    }