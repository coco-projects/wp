<?php

    namespace Coco\wp;

    class ArticleContent
    {
        protected array $contents = [];

        public function video($src): static
        {
            $this->contents[] = static::wpWrapper('video', '<figure class="wp-block-video"><video controls src="' . $src . '"></video></figure>');

            return $this;
        }

        public function audio($src): static
        {
            $this->contents[] = static::wpWrapper('audio', '<figure class="wp-block-audio"><audio controls src="' . $src . '"></audio></figure>');

            return $this;
        }

        public function image($src): static
        {
            $this->contents[] = static::wpWrapper('image', '<figure class="wp-block-image size-large"><img src="' . $src . '" alt=""/></figure>');

            return $this;
        }

        public function hr(): static
        {
            $this->contents[] = static::separator();

            return $this;
        }

        public function a($link, $text = ''): static
        {
            !$text && $text = $link;

            $this->contents[] = static::paragraph("<a target='_blank' href='{$link}'>$text</a>");

            return $this;
        }

        public function aBlock($link, $text = ''): static
        {
            !$text && $text = $link;

            $this->contents[] = static::paragraph("<p><a target='_blank' href='{$link}'>$text</a></p>");

            return $this;
        }

        public function p($text): static
        {
            $this->contents[] = static::paragraph("<p>$text</p>");

            return $this;
        }

        /*-----------------------------------------------------------------------*/

        public static function paragraph(string $content): string
        {
            return static::wpWrapper('paragraph', $content);
        }

        public static function html(string $content): string
        {
            return static::wpWrapper('html', $content);
        }

        public static function separator(): string
        {
            return static::wpWrapper('separator', '<hr class="wp-block-separator has-alpha-channel-opacity"/>');
        }

        public static function wpWrapper(string $tagName, string $content = '', $attrs = []): string
        {
            $temp = <<<str
<!-- wp:__TAG__ __ATTR__ -->
__CONTENT__
<!-- /wp:__TAG__ -->


str;

            return strtr($temp, [
                "__TAG__"     => $tagName,
                "__CONTENT__" => $content,
                "__ATTR__"    => count($attrs) ? json_encode($attrs, 256) : '',
            ]);
        }


        /*-----------------------------------------------------------------------*/
        public function __toString(): string
        {
            return implode('', $this->contents);
        }
    }