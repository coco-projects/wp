<?php

    namespace Coco\wp;

    class ArticleContent
    {

        public static function table(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('table', $content, $attrs);
        }

        public static function buttons(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('buttons', $content, $attrs);
        }

        public static function button(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('button', $content, $attrs);
        }

        public static function audio(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('audio', $content, $attrs);
        }

        public static function video(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('video', $content, $attrs);
        }

        public static function shortcode(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('shortcode', $content, $attrs);
        }

        public static function paragraph(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('paragraph', $content, $attrs);
        }

        public static function image(mixed $content, $attrs = []): string
        {
            return static::wpWrapper('image', $content, $attrs);
        }

        public static function html(mixed $content): string
        {
            return static::wpWrapper('html', $content);
        }

        public static function separator(): string
        {
            return static::wpWrapper('separator', '<hr class="wp-block-separator has-alpha-channel-opacity"/>');
        }

        /*-------------------------------------------------------*/

        public static function wpWrapper(string $tagName, mixed $content = '', $attrs = []): string
        {
            $str = static::contentToString($content);

            $temp = <<<str
<!-- wp:__TAG__ __ATTR__ -->
__CONTENT__
<!-- /wp:__TAG__ -->


str;

            return strtr($temp, [
                "__TAG__"     => $tagName,
                "__CONTENT__" => $str,
                "__ATTR__"    => count($attrs) ? json_encode($attrs, 256) : '',
            ]);
        }

        private static function contentToString(mixed $content)
        {
            static $processed = []; // 记录已处理的对象或数组，避免死循环

            $str = '';

            if (is_array($content))
            {
                $t = [];
                foreach ($content as $k => $v)
                {
                    $t[] = static::contentToString($v);
                }
                $str = implode('', $t);
            }
            elseif (is_callable($content))
            {
                $str = call_user_func($content);
            }
            elseif (is_object($content))
            {
                // 检查是否已经处理过这个对象，避免死循环
                if (in_array(spl_object_hash($content), $processed))
                {
                    return ''; // 如果已经处理过，返回空字符串
                }

                $processed[] = spl_object_hash($content); // 标记这个对象已经处理过

                // 检查对象是否实现了 __toString() 方法
                if (method_exists($content, '__toString'))
                {
                    $str = (string)$content;
                }
                else
                {
                    // 如果没有实现 __toString() 方法，忽略该对象
                    $str = '';
                }
            }
            else
            {
                // 如果是其他类型，直接转为字符串
                $str = (string)$content;
            }

            return $str;
        }

    }