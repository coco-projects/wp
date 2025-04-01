<?php

    namespace Coco\wp;

    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\htmlBuilder\dom\SingleTag;

    class WpTag
    {

        public static function buttons(mixed $buttons): string
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
            return ArticleContent::paragraph([
                Tag::p(Tag::a($link, $text, $target)),
            ]);
        }

        public static function p(mixed $content): string
        {
            return ArticleContent::paragraph([
                Tag::p($content),
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

        public static function video(string $src): string
        {
            return ArticleContent::video([
                Tag::figure([
                    Tag::video($src),
                ], [
                    'wp-block-video',
                ]),
            ]);
        }

        public static function audio(string $src): string
        {
            return ArticleContent::audio([
                Tag::figure([
                    Tag::audio($src),
                ], [
                    'wp-block-audio',
                ]),
            ]);
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

        public static function DPlayer(string $url, string $theme = '#FADFA3', string $lang = 'zh-cn', string $pic = '', string $thumbnails = '', bool $unlimited = true, string $type = 'auto', string $logo = null, float $volume = 0.7, bool $loop = false, bool $screenshot = true, bool $hotkey = true, bool $preload = false, bool $mutex = false, bool $autoplay = false): string
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

        public static function doubleShortcode($name, $text, $kv)
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