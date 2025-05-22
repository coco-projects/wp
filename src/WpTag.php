<?php

    namespace Coco\wp;

    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\htmlBuilder\dom\SingleTag;

    class WpTag
    {
        const  bgColorMap = [
            "default" => [
                "bg"   => "#ffffff",
                "text" => "#111111",
            ],
            "red"     => [
                "bg"   => "#ff5722",
                "text" => "#ffffff",
            ],
            "orange"  => [
                "bg"   => "#ffb800",
                "text" => "#ffffff",
            ],
            "green"   => [
                "bg"   => "#16baaa",
                "text" => "#ffffff",
            ],
            "blue"    => [
                "bg"   => "#1e9fff",
                "text" => "#ffffff",
            ],
            "purple"  => [
                "bg"   => "#a233c6",
                "text" => "#ffffff",
            ],
            "black"   => [
                "bg"   => "#2f363c",
                "text" => "#ffffff",
            ],
            "gray"    => [
                "bg"   => "#fafafa",
                "text" => "#5f5f5f",
            ],
        ];

        const  textColorMap = [
            "default" => [
                "bg"   => "",
                "text" => "#555555",
            ],
            "red"     => [
                "bg"   => "",
                "text" => "#ff5722",
            ],
            "orange"  => [
                "bg"   => "",
                "text" => "#ffb800",
            ],
            "green"   => [
                "bg"   => "",
                "text" => "#16baaa",
            ],
            "blue"    => [
                "bg"   => "",
                "text" => "#1e9fff",
            ],
            "purple"  => [
                "bg"   => "",
                "text" => "#a233c6",
            ],
            "black"   => [
                "bg"   => "",
                "text" => "#2f363c",
            ],
            "gray"    => [
                "bg"   => "",
                "text" => "#999999",
            ],
        ];

        /**
         * @param $texts
         *
         * @return string
         */
        public static function groupConstrained($texts): string
        {
            return static::group($texts, 'constrained');
        }

        /**
         * @param array  $texts
         * @param bool   $isWrap
         * @param string $justifyContent right,center,left,space-between
         *
         * @return string
         */
        public static function groupFlexHorizontal(array $texts, bool $isWrap = true, string $justifyContent = 'left'): string
        {
            $flexWrap = $isWrap ? 'wrap' : 'nowrap';

            return static::group($texts, 'flex', $flexWrap, $justifyContent, 'horizontal');
        }

        /**
         * @param array  $texts
         * @param bool   $isWrap
         * @param string $justifyContent right,center,left,stretch
         *
         * @return string
         */
        public static function groupFlexVertical(array $texts, bool $isWrap = true, string $justifyContent = 'left'): string
        {
            $flexWrap = $isWrap ? 'wrap' : 'nowrap';

            return static::group($texts, 'flex', $flexWrap, $justifyContent, 'vertical');
        }

        /**
         * @param array    $texts
         * @param int|null $columnCount
         * @param int|null $minimumColumnWidth
         *
         * @return string
         */
        public static function groupGrid(array $texts, ?int $columnCount = 3, ?int $minimumColumnWidth = null): string
        {
            return static::group($texts, 'grid', '', '', '', $columnCount, $minimumColumnWidth);
        }

        /**
         * @param array    $texts
         * @param string   $type           constrained,flex,grid
         * @param string   $flexWrap       wrap,nowrap
         * @param string   $justifyContent right,center,left,stretch,space-between
         * @param string   $orientation    vertical,horizontal
         * @param int|null $columnCount
         * @param int|null $minimumColumnWidth
         *
         * @return string
         */
        protected static function group(array $texts, string $type, string $flexWrap = 'nowrap', string $justifyContent = 'left', string $orientation = 'horizontal', ?int $columnCount = 3, ?int $minimumColumnWidth = null): string
        {
            $attrs                   = [];
            $attrs['layout']['type'] = $type;

            if (in_array($type, ['constrained']))
            {
            }

            if (in_array($type, ['flex']))
            {
                $attrs['layout']['orientation']    = $orientation;
                $attrs['layout']['flexWrap']       = $flexWrap;
                $attrs['layout']['justifyContent'] = $justifyContent;
            }

            if (in_array($type, ['grid']))
            {
                $attrs['layout']['minimumColumnWidth'] = null;
                if (is_int($minimumColumnWidth))
                {
                    $attrs['layout']['minimumColumnWidth'] = $minimumColumnWidth . 'rem';
                }

                $attrs['layout']['columnCount'] = null;
                if (is_int($columnCount))
                {
                    $attrs['layout']['columnCount'] = $columnCount;
                }
            }

            return ArticleContent::group(Tag::div($texts, ['wp-block-group']), $attrs);
        }

        public static function columnsAvg(array $contents): string
        {
            $count = count($contents);

            $avgWidth = (100 / $count);

            $inner = [];
            foreach ($contents as $k => $v)
            {
                $inner[] = ArticleContent::column(Tag::column($v, $avgWidth . "%"), [
                    "width" => $avgWidth . "%",
                ]);
            }

            return ArticleContent::columns(Tag::columns($inner));
        }

        public static function columns(array $contents): string
        {
            $inner = [];
            foreach ($contents as $k => $v)
            {
                $width = $v['width'] ?? '';
                $attrs = [];

                if ($width)
                {
                    $attrs['width'] = $width;
                }

                $inner[] = ArticleContent::column(Tag::column($v['content'], $width), $attrs);
            }

            return ArticleContent::columns(Tag::columns($inner));
        }

        /**
         * @param array  $contents
         * @param int    $defaultIndex
         * @param string $direction top,right,left
         *
         */
        public static function zibllTabs(array $contents, int $defaultIndex = 1, string $direction = 'top'): string
        {
            $navItems     = [];
            $contentItems = [];

            $columns = [];

            if ($defaultIndex > count($contents))
            {
                $defaultIndex = 1;
            }

            foreach ($contents as $k => $v)
            {
                $selected  = !$k;
                $columns[] = $v['title'];

                $navItems[] = Tag::li(Tag::a('javascript:;', $v['title'], '', ["post-tab-toggle"], ["tab-id" => $k]), [
                    $selected ? 'active' : '',
                ]);

                $tabAttr = [];
                if ($k > 0)
                {
                    $tabAttr['id'] = $k;
                }

                if (($defaultIndex) > 0)
                {
                    $tabAttr['tabActive'] = $defaultIndex - 1;
                }

                $contentItems[] = ArticleContent::zibllblockTab(Tag::div($v['content'], [
                    "tab-pane",
                    "fade",
                    $selected ? 'active' : '',
                    $selected ? 'in' : '',
                ], ["tab-id" => $k]), $tabAttr);
            }

            $layoutDiv = 'nav-top';
            $layoutWp  = '';
            if ($direction == 'left')
            {
                $layoutDiv = 'nav-left';
                $layoutWp  = 'nav-left';
            }

            if ($direction == 'right')
            {
                $layoutDiv = 'nav-left nav-right';
                $layoutWp  = 'nav-left nav-right';
            }

            $tabsAttr = [];

            $tabsAttr['tabHeaders'] = $columns;

            if (($defaultIndex) > 0)
            {
                $tabsAttr['tabActive'] = $defaultIndex - 1;
            }
            if ($layoutWp)
            {
                $tabsAttr['layout'] = $layoutWp;
            }

            return ArticleContent::zibllblockTabs(Tag::div([

                //导航
                Tag::div($navItems, [
                    "list-inline",
                    "scroll-x",
                    "mini-scrollbar",
                    "tab-nav-theme",
                ]),

                //正文
                Tag::div($contentItems, ['tab-content']),

            ], [
                "mb20",
                "post-tab",
                $layoutDiv,
            ]), $tabsAttr);

        }

        public static function listQuote(array $texts): string
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

        public static function list(array $texts, string $color = 'default', string $fontSize = '14px'): string
        {
            if (!isset(static::textColorMap[$color]))
            {
                $color = 'gray';
            }
            $textColor   = static::textColorMap[$color]['text'];
            $textBgColor = static::textColorMap[$color]['bg'];

            $item = [];
            foreach ($texts as $text)
            {
                $item[] = ArticleContent::listItem(Tag::li($text));
            }

            return ArticleContent::list(Tag::ul($item, $textColor, $textBgColor, $fontSize), [
                'style' => [
                    'elements'   => [
                        "link" => [
                            "color" => [
                                "background" => $textBgColor,
                                "text"       => $textColor,
                            ],
                        ],
                    ],
                    "color"      => [
                        "background" => $textBgColor,
                        "text"       => $textColor,
                    ],
                    "typography" => [
                        "fontSize" => $fontSize,
                    ],
                ],
            ]);
        }

        public static function details(string $title, mixed $texts, bool $isOpen = true, string $color = 'default', string $fontSize = '14px'): string
        {
            if (!isset(static::textColorMap[$color]))
            {
                $color = 'default';
            }
            $fontColor       = static::textColorMap[$color]['text'];
            $backgroundColor = static::textColorMap[$color]['bg'];

            return ArticleContent::details(Tag::details([
                Tag::summary($title),
                $texts,
            ], $fontColor, $backgroundColor, $fontSize), [
                'style' => [
                    "showContent" => $isOpen ? "true" : "false",
                    'elements'    => [
                        "link" => [
                            "color" => [
                                "background" => $backgroundColor,
                                "text"       => $fontColor,
                            ],
                        ],
                    ],
                    "color"       => [
                        "background" => $backgroundColor,
                        "text"       => $fontColor,
                    ],
                    'typography'  => [
                        'fontSize' => $fontSize,
                    ],
                ],
            ]);
        }

        public static function zibllDetails(string $title, mixed $texts, bool $isOpen = true): string
        {
            $id_hash = 'collapse_' . rand(10000, 99999);

            $head = Tag::div([
                Tag::i('', [
                    "fa",
                    "fa-plus",
                ]),
                Tag::strong($title, ["biaoti"]),
            ], [
                'panel-heading',
                $isOpen ? "" : "collapsed",

            ], [
                "href"          => '#' . $id_hash,
                "data-toggle"   => "collapse",
                "aria-controls" => "collapseExample",
            ]);

            $content = Tag::div([

                Tag::div($texts, [
                    'panel-body',
                ]),

            ], [
                'collapse',
                $isOpen ? "in" : "",
            ], [
                "id" => $id_hash,
            ]);

            $attr = [];
            if (!$isOpen)
            {
                $attr['isshow'] = false;
            }

            return ArticleContent::zibllblockCollapse(Tag::div(Tag::div([
                $head,
                $content,
            ], [
                'panel',
            ], [
                'data-theme'  => 'panel',
                'data-isshow' => $isOpen ? "true" : "false",
            ]), [
                "wp-block-zibllblock-collapse",
            ]), $attr);

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
                $fontSize     = $button['fontSize'] ?? '14px';
                $borderRadius = $button['borderRadius'] ?? '12px';
                $textColor    = $button['textColor'] ?? '#dddddd';
                $textBgColor  = $button['textBgColor'] ?? '#3d3d3d';

                if (isset($button['btnColor']) && isset(static::bgColorMap[$button['btnColor']]))
                {
                    $textColor   = static::bgColorMap[$button['btnColor']]['text'];
                    $textBgColor = static::bgColorMap[$button['btnColor']]['bg'];
                }

                $btn = Tag::button($button['link'], $button['text'], $button['target'] ?? '_blank', $fontSize, $borderRadius, $textColor, $textBgColor,);

                $attrs = [
                    "style" => [
                        'elements'   => [
                            'link' => [
                                'color' => [
                                    'text' => $textColor,
                                ],
                            ],
                        ],
                        'color'      => [
                            'text'       => $textColor,
                            'background' => $textBgColor,
                        ],
                        'typography' => [
                            'fontSize' => $fontSize,
                        ],
                        'border'     => [
                            'radius' => $borderRadius,
                        ],
                    ],
                ];

                $buttonsElements[] = ArticleContent::button($btn, $attrs);
            }

            return ArticleContent::buttons(Tag::buttons($buttonsElements));
        }

        /**
         * @param string $src
         * @param int    $width
         * @param int    $height
         * @param string $aspectRatio
         * @param string $scale cover,contain
         *
         * @return string
         */
        public static function image(string $src, int $width = 200, int $height = 0, string $aspectRatio = '9/16', string $scale = 'cover'): string
        {
            $aspectRatioMap = [
                'auto',
                '1',
                '2/3',
                '3/2',
                '4/3',
                '3/4',
                '9/16',
                '16/9',
            ];

            if (!in_array($aspectRatio, $aspectRatioMap))
            {
                $aspectRatio = 'auto';
            }

            $attr  = [];
            $style = [];

            $width_  = 0;
            $height_ = 0;

            if ($width || $height)
            {
                if ($width > 1)
                {
                    $width_  = $width . 'px';
                    $height_ = 'auto';
                }

                if ($height > 1)
                {
                    $width_  = 'auto';
                    $height_ = $height . 'px';
                }

                $attr['width']  = $width_;
                $attr['height'] = $height_;

                $style['width']  = $width_;
                $style['height'] = $height_;
            }

            if ($aspectRatio !== 'auto')
            {
                $attr['aspectRatio']   = $aspectRatio;
                $style['aspect-ratio'] = $aspectRatio;
            }

            $style['object-fit'] = $scale;
            $attr['scale']       = $scale;

            $attr['sizeSlug']        = 'full';
            $attr['linkDestination'] = 'none';
            $attr['className']       = "is-style-default";

            return ArticleContent::image([
                Tag::figure([
                    Tag::img($src, '', [], $style),
                ], [
                    "wp-block-image",
                    "size-full",
                    "is-resized",
                    "is-style-default",
                ]),
            ], $attr);
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
            return ArticleContent::heading([Tag::h6($content)], ["level" => 6]);
        }

        public static function aBlock(string $link, string $text = '', string $color = 'default', string $target = "_blank"): string
        {
            if (!isset(static::textColorMap[$color]))
            {
                $color = 'default';
            }
            $fontColor       = static::textColorMap[$color]['text'];
            $backgroundColor = static::textColorMap[$color]['bg'];

            return ArticleContent::paragraph([Tag::a($link, Tag::p($text, $fontColor, $backgroundColor), $target)]);
        }

        public static function p(mixed $content, string $color = 'default', $fontSize = '14px'): string
        {
            if (!isset(static::textColorMap[$color]))
            {
                $color = 'default';
            }
            $fontColor       = static::textColorMap[$color]['text'];
            $backgroundColor = static::textColorMap[$color]['bg'];

            return ArticleContent::paragraph([Tag::p($content, $fontColor, $backgroundColor, $fontSize)]);
        }

        public static function video(string $src): string
        {
            return ArticleContent::video([Tag::figure([Tag::video($src)], ['wp-block-video'])]);
        }

        public static function audio(string $src): string
        {
            return ArticleContent::audio([Tag::figure([Tag::audio($src)], ['wp-block-audio'])]);
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

        public static function tagCloudCategory(string $smallestFontSize = '10pt', string $largestFontSize = '28pt', int $numberOfTags = 100, bool $showTagCounts = true): string
        {
            return static::tagCloud('category', $smallestFontSize, $largestFontSize, $numberOfTags, $showTagCounts);
        }

        public static function tagCloudPostTag(string $smallestFontSize = '10pt', string $largestFontSize = '28pt', int $numberOfTags = 100, bool $showTagCounts = true): string
        {
            return static::tagCloud('post_tag', $smallestFontSize, $largestFontSize, $numberOfTags, $showTagCounts);
        }

        public static function tagCloudTopics(string $smallestFontSize = '10pt', string $largestFontSize = '28pt', int $numberOfTags = 100, bool $showTagCounts = true): string
        {
            return static::tagCloud('topics', $smallestFontSize, $largestFontSize, $numberOfTags, $showTagCounts);
        }

        public static function tagCloudForumTopic(string $smallestFontSize = '10pt', string $largestFontSize = '28pt', int $numberOfTags = 100, bool $showTagCounts = true): string
        {
            return static::tagCloud('forum_topic', $smallestFontSize, $largestFontSize, $numberOfTags, $showTagCounts);
        }

        public static function tagCloudForumTag(string $smallestFontSize = '10pt', string $largestFontSize = '28pt', int $numberOfTags = 100, bool $showTagCounts = true): string
        {
            return static::tagCloud('forum_tag', $smallestFontSize, $largestFontSize, $numberOfTags, $showTagCounts);
        }

        /**
         * @param string $taxonomy category,post_tag,topics,forum_topic,forum_tag
         * @param string $smallestFontSize
         * @param string $largestFontSize
         * @param int    $numberOfTags
         * @param bool   $showTagCounts
         *
         * @return string
         */
        protected static function tagCloud(string $taxonomy = 'tags', string $smallestFontSize = '10pt', string $largestFontSize = '28pt', int $numberOfTags = 100, bool $showTagCounts = true): string
        {
            $attrs = [
                "numberOfTags"     => $numberOfTags,
                "showTagCounts"    => $showTagCounts,
                "smallestFontSize" => $smallestFontSize,
                "largestFontSize"  => $largestFontSize,
                "taxonomy"         => $taxonomy,
            ];

            return ArticleContent::wpSingleWrapper('tag-cloud', $attrs);
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