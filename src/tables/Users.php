<?php

    namespace Coco\wp\tables;

    use Coco\tableManager\TableAbstract;

    class Users extends TableAbstract
    {
        public string $comment = '用户';

        public array $fieldsSqlMap = [
            "ID"                  => "`__FIELD__NAME__` bigint(20) unsigned NOT NULL AUTO_INCREMENT,",
            "user_login"          => "`__FIELD__NAME__` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "user_pass"           => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "user_nicename"       => "`__FIELD__NAME__` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "user_email"          => "`__FIELD__NAME__` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "user_url"            => "`__FIELD__NAME__` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "user_registered"     => "`__FIELD__NAME__` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',",
            "user_activation_key" => "`__FIELD__NAME__` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
            "user_status"         => "`__FIELD__NAME__` int(11) NOT NULL DEFAULT '0',",
            "display_name"        => "`__FIELD__NAME__` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',",
        ];

        protected array $indexSentence = [
            "user_login"    => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
            "user_nicename" => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
            "user_email"    => "KEY `__INDEX__NAME___index` ( __FIELD__NAME__ ),",
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

        public function setUserLoginField(string $value): static
        {
            $this->setFeildName('user_login', $value);

            return $this;
        }

        public function getUserLoginField(): string
        {
            return $this->getFieldName('user_login');
        }

        public function setUserPassField(string $value): static
        {
            $this->setFeildName('user_pass', $value);

            return $this;
        }

        public function getUserPassField(): string
        {
            return $this->getFieldName('user_pass');
        }

        public function setUserNicenameField(string $value): static
        {
            $this->setFeildName('user_nicename', $value);

            return $this;
        }

        public function getUserNicenameField(): string
        {
            return $this->getFieldName('user_nicename');
        }

        public function setUserEmailField(string $value): static
        {
            $this->setFeildName('user_email', $value);

            return $this;
        }

        public function getUserEmailField(): string
        {
            return $this->getFieldName('user_email');
        }

        public function setUserUrlField(string $value): static
        {
            $this->setFeildName('user_url', $value);

            return $this;
        }

        public function getUserUrlField(): string
        {
            return $this->getFieldName('user_url');
        }

        public function setUserRegisteredField(string $value): static
        {
            $this->setFeildName('user_registered', $value);

            return $this;
        }

        public function getUserRegisteredField(): string
        {
            return $this->getFieldName('user_registered');
        }

        public function setUserActivationKeyField(string $value): static
        {
            $this->setFeildName('user_activation_key', $value);

            return $this;
        }

        public function getUserActivationKeyField(): string
        {
            return $this->getFieldName('user_activation_key');
        }

        public function setUserStatusField(string $value): static
        {
            $this->setFeildName('user_status', $value);

            return $this;
        }

        public function getUserStatusField(): string
        {
            return $this->getFieldName('user_status');
        }

        public function setDisplayNameField(string $value): static
        {
            $this->setFeildName('display_name', $value);

            return $this;
        }

        public function getDisplayNameField(): string
        {
            return $this->getFieldName('display_name');
        }

    }