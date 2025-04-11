<?php

    namespace Coco\wp;

    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\htmlBuilder\dom\SingleTag;

    class WpTag
    {
        public static function h1(mixed $content): string
        {
            return ArticleContent::heading([Tag::h1($content)], ["level" => 1]);
        }

        public static function h2(mixed $content): string
        {
            return ArticleContent::heading([Tag::h2($content)], ["level" => 2]);
        }

        public static function h3(mixed $content): string
        {
            return ArticleContent::heading([Tag::h3($content)], ["level" => 3]);
        }

        public static function h4(mixed $content): string
        {
            return ArticleContent::heading([Tag::h4($content)], ["level" => 4]);
        }

        public static function h5(mixed $content): string
        {
            return ArticleContent::heading([Tag::h5($content)], ["level" => 5]);
        }

        public static function h6(mixed $content): string
        {
            return ArticleContent::heading([Tag::h5($content)], ["level" => 6]);
        }

        public static function quote(array $texts): string
        {
            $paragraphs = [];
            foreach ($texts as $text)
            {
                $paragraphs[] = ArticleContent::paragraph(Tag::p($text));
            }

            return ArticleContent::quote(Tag::blockquote($paragraphs, [
                'wp-block-quote',
            ]));
        }

        public static function gallery(array $images): string
        {
            $imagesElements = [];

            foreach ($images as $k => $v)
            {
                $figcaption = '';

                if (isset($v['caption']) && $v['caption'])
                {
                    $figcaption = Tag::figcaption($v['caption'], ['wp-element-caption']);
                }

                $imagesElements[] = ArticleContent::image([
                    Tag::figure([
                        Tag::img($v['src']),
                        $figcaption,
                    ], [
                        'wp-block-image',
                        'size-large',
                    ]),
                ], [
                    'sizeSlug'        => 'large',
                    'linkDestination' => 'none',
                ]);
            }

            return ArticleContent::gallery(Tag::figure($imagesElements, [
                'wp-block-gallery',
                'has-nested-images',
                'columns-default',
                'is-cropped',
            ]), ['linkTo' => 'none']);
        }

        public static function buttons(array $buttons): string
        {
            $buttonsElements = [];

            foreach ($buttons as $button)
            {
                $buttonsElements[] = ArticleContent::button(Tag::button($button['link'], $button['text'], $button['target'] ?? '_blank'), [
                    'backgroundColor' => 'luminous-vivid-orange',
                    'className'       => 'is-style-fill',
                    'style'           => [
                        'border'     => [
                            'radius' => '12px',
                        ],
                        'typography' => [
                            'fontSize' => '14px',
                        ],
                    ],
                ]);
            }

            return ArticleContent::buttons(Tag::buttons($buttonsElements), [
                'layout' => [
                    'type'           => 'flex',
                    'justifyContent' => 'left',
                ],
            ]);
        }

        public static function image(string $src): string
        {
            return ArticleContent::image([
                Tag::figure([
                    Tag::img($src),
                ], [
                    'wp-block-image',
                    'aligncenter',
                    'size-large',
                ]),
            ]);
        }

        public static function imageWithLink(string $src, string $link, string $figcaption = ''): string
        {
            return ArticleContent::image([
                Tag::figure([
                    Tag::a($link, Tag::img($src)),
                    $figcaption ? Tag::figcaption($figcaption, ['wp-element-caption']) : '',
                ], [
                    'wp-block-image',
                    'aligncenter',
                ]),
            ]);
        }

        public static function hr(): string
        {
            return ArticleContent::separator();
        }

        public static function aBlock(string $link, string $text = '', string $target = "_blank"): string
        {
            return ArticleContent::paragraph([Tag::p(Tag::a($link, $text, $target))]);
        }

        public static function p(mixed $content): string
        {
            return ArticleContent::paragraph([Tag::p($content)]);
        }

        public static function video(string $src): string
        {
            return ArticleContent::video([Tag::figure([Tag::video($src)], ['wp-block-video'])]);
        }

        public static function audio(string $src): string
        {
            return ArticleContent::audio([Tag::figure([Tag::audio($src)], ['wp-block-audio'])]);
        }

        /**
         * https://noorsplugin.com/wordpress-video-plugin/
         *
         * @param string $src
         * @param bool   $autoplay
         *
         * @return string
         */
        public static function easyVideoPlayer(string $src, bool $autoplay = false): string
        {
            $shortcode = static::singleShortcode('evp_embed_video', [
                "url"      => $src,
                "autoplay" => $autoplay,
            ]);

            return ArticleContent::shortcode($shortcode);
        }

        public static function dPlayer(string $url, string $theme = '#FADFA3', string $lang = 'zh-cn', string $pic = '', string $thumbnails = '', bool $unlimited = true, string $type = 'auto', string $logo = null, float $volume = 0.7, bool $loop = false, bool $screenshot = true, bool $hotkey = true, bool $preload = false, bool $mutex = false, bool $autoplay = false): string
        {
            $shortcode = static::singleShortcode('dplayer', [
                "url"        => $url,
                "autoplay"   => $autoplay,
                "theme"      => $theme,
                "lang"       => $lang,
                "pic"        => $pic,
                "thumbnails" => $thumbnails,
                "unlimited"  => $unlimited,
                "type"       => $type,
                "logo"       => $logo,
                "volume"     => $volume,
                "loop"       => $loop,
                "screenshot" => $screenshot,
                "hotkey"     => $hotkey,
                "preload"    => $preload,
                "mutex"      => $mutex,
            ]);

            return ArticleContent::shortcode($shortcode);
        }

        public static function hideContent($content): string
        {
            $shortcode = static::doubleShortcode('hidecontent', $content, ["type" => 'payshow']);

            return ArticleContent::shortcode($shortcode);
        }

        public static function singleShortcode($name, $kv): string
        {
            $value   = [];
            $value[] = "[$name ";

            foreach ($kv as $k => $v)
            {
                if (is_bool($v))
                {
                    $v = static::boolToString($v);
                }

                if (is_numeric($k))
                {
                    $value[] = $v;
                }
                if (is_string($k))
                {
                    $value[] = $k . "=\"" . $v . "\"";;
                }
            }
            $value[] = "]";

            return implode(' ', $value);
        }

        public static function doubleShortcode($name, $text, $kv): string
        {
            $value   = [];
            $value[] = "[$name ";

            foreach ($kv as $k => $v)
            {
                if (is_bool($v))
                {
                    $v = static::boolToString($v);
                }

                if (is_numeric($k))
                {
                    $value[] = $v;
                }
                if (is_string($k))
                {
                    $value[] = $k . "=\"" . $v . "\"";;
                }
            }
            $value[] = "]" . $text . "[/" . $name . "]";

            return implode(' ', $value);
        }

        private static function boolToString(mixed $value): string
        {
            return (!!$value ? 'true' : 'false');
        }
    }