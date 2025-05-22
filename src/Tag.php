<?php

    namespace Coco\wp;

    use Coco\htmlBuilder\attrs\RawAttr;
    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\htmlBuilder\dom\SingleTag;
    use Coco\htmlBuilder\dom\tags\Div;

    class Tag
    {
        public static function button(string $link, string $text = '', string $target = "_blank", string $fontSize = "14px", string $borderRadius = "10px", string $fontColor = "#9bff9b", string $backgroundColor = "#cccccc"): string
        {
            return Div::ins()->inner(function(DoubleTag $this_, array &$inner) use (
                $fontSize, $borderRadius, $fontColor, $backgroundColor, $text, $link, $target
            ) {
                $this_->getAttr('class')->addAttrsArray([
                    "wp-block-button",
                    "has-custom-font-size",
                ]);

                $this_->getAttr('style')->importKv([
                    "font-size" => $fontSize,
                ]);

                $inner[] = DoubleTag::ins('a')->inner(function(DoubleTag $this_, array &$inner) use (
                    $fontSize, $borderRadius, $fontColor, $backgroundColor, $text, $link, $target
                ) {
                    $this_->getAttr('href')->setAttrKv('href', $link);
                    $this_->getAttr('target')->setAttrKv('target', $target);

                    $this_->getAttr('class')->addAttrsArray([
                        "wp-block-button__link",
                        "has-text-color",
                        "has-background",
                        "has-link-color",
                        "wp-element-button",
                    ]);

                    $this_->getAttr('style')->importKv([
                        "border-radius"    => $borderRadius,
                        "color"            => $fontColor,
                        "background-color" => $backgroundColor,
                    ]);
                    $inner[] = $text;
                });
            })->render();
        }

        public static function buttons(mixed $content): string
        {
            return Div::ins()->inner(function(DoubleTag $this_, array &$inner) use ($content) {
                $this_->getAttr('class')->addAttrsArray(['wp-block-buttons']);

                $inner[] = $content;
            })->render();
        }

        public static function a(string $link, string $text = '', string $target = "_blank", array $classes = [], array $kvAttr = []): string
        {
            !$text && $text = $link;

            return DoubleTag::ins('a')
                ->inner(function(DoubleTag $this_, array &$inner) use ($text, $link, $target, $classes, $kvAttr) {
                    $this_->getAttr('class')->addAttrsArray($classes);
                    $this_->getAttr('href')->setAttrKv('href', $link);
                    $this_->getAttr('target')->setAttrKv('target', $target);

                    $this_->attrsRegistry->appendAttrKvArr($kvAttr);

                    $inner[] = $text;
                })->render();
        }

        public static function ul(mixed $content, $fontColor = '#111111', $backgroundColor = '#eeeeee', $fontSize = '14px', array $classes = []): string
        {
            return DoubleTag::ins('ul')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes, $fontColor, $fontSize, $backgroundColor) {
                    $this_->getAttr('class')->addAttrsArray($classes);
                    $this_->getAttr('class')->addAttrsArray([
                        "wp-block-list",
                        "has-text-color",
                        "has-background",
                        "has-link-color",
                    ]);

                    $attr = [
                        "color"     => $fontColor,
                        "font-size" => $fontSize,
                    ];
                    if ($backgroundColor)
                    {
                        $attr["background-color"] = $backgroundColor;
                    }
                    $this_->getAttr('style')->importKv($attr);

                    $inner[] = $content;
                })->render();
        }

        public static function li(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('li')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);

                $inner[] = $content;
            })->render();
        }

        public static function span(mixed $content, $fontColor = '#999999', $backgroundColor = '', $fontSize = '14px', array $classes = []): string
        {
            return DoubleTag::ins('span')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes, $fontSize, $fontColor, $backgroundColor) {
                    $this_->getAttr('class')->addAttrsArray($classes);

                    $attr = [
                        "color"     => $fontColor,
                        "font-size" => $fontSize,
                    ];
                    if ($backgroundColor)
                    {
                        $attr["background-color"] = $backgroundColor;
                    }
                    $this_->getAttr('style')->importKv($attr);

                    $inner[] = $content;
                })->render();
        }

        public static function i(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('i')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);

                $inner[] = $content;
            })->render();
        }

        public static function strong(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('strong')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);

                $inner[] = $content;
            })->render();
        }

        public static function details(mixed $content, $fontColor = '#111111', $backgroundColor = '#eeeeee', $fontSize = '14px', array $classes = []): string
        {
            return DoubleTag::ins('details')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes, $fontSize, $fontColor, $backgroundColor) {
                    $this_->getAttr('class')->addAttrsArray($classes);
                    $this_->getAttr('class')->addAttrsArray([
                        "wp-block-details",
                        "has-text-color",
                        "has-background",
                        "has-link-color",
                    ]);

                    $attr = [
                        "color"     => $fontColor,
                        "font-size" => $fontSize,
                    ];
                    if ($backgroundColor)
                    {
                        $attr["background-color"] = $backgroundColor;
                    }
                    $this_->getAttr('style')->importKv($attr);

                    $inner[] = $content;
                })->render();
        }

        public static function summary(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('summary')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);

                $inner[] = $content;
            })->render();
        }

        public static function columns(mixed $content, array $classes = []): string
        {
            return Div::ins()->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    "wp-block-columns",
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function column(mixed $content, string $width, array $classes = []): string
        {
            return Div::ins()->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes, $width) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    "wp-block-column",
                ]);

                if ((int)$width)
                {
                    $this_->getAttr('style')->importKv([
                        "flex-basis" => $width,
                    ]);
                }

                $inner[] = $content;
            })->render();
        }

        public static function p(mixed $content, $fontColor = '#111111', $backgroundColor = '#eeeeee', $fontSize = '14px', array $classes = []): string
        {
            return DoubleTag::ins('p')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes, $fontSize, $fontColor, $backgroundColor) {
                    $this_->getAttr('class')->addAttrsArray($classes);


                    $attr = [
                        "color"     => $fontColor,
                        "font-size" => $fontSize,
                    ];
                    if ($backgroundColor)
                    {
                        $attr["background-color"] = $backgroundColor;
                    }
                    $this_->getAttr('style')->importKv($attr);

                    $inner[] = $content;
                })->render();
        }

        public static function div(mixed $content, array $classes = [], array $kvAttr = []): string
        {
            return DoubleTag::ins('div')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $kvAttr, $classes) {
                    $this_->getAttr('class')->addAttrsArray($classes);
                    $this_->attrsRegistry->appendAttrKvArr($kvAttr);

                    $inner[] = $content;
                })->render();
        }

        public static function h1(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('h1')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-heading',
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function h2(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('h2')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-heading',
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function h3(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('h3')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-heading',
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function h4(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('h4')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-heading',
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function h5(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('h5')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-heading',
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function h6(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('h6')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-heading',
                ]);
                $inner[] = $content;
            })->render();
        }

        public static function blockquote(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('blockquote')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                    $this_->getAttr('class')->addAttrsArray($classes);

                    $inner[] = $content;
                })->render();
        }

        public static function figure(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('figure')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                $this_->getAttr('class')->addAttrsArray($classes);

                $inner[] = $content;
            })->render();
        }

        public static function figcaption(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('figcaption')
                ->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
                    $this_->getAttr('class')->addAttrsArray($classes);

                    $inner[] = $content;
                })->render();
        }

        public static function img(string $src, string $alt = '', array $classes = [], array $style = []): string
        {
            return SingleTag::ins('img')
                ->inner(function(SingleTag $this_, array &$inner) use ($style, $src, $alt, $classes) {
                    $this_->getAttr('src')->setAttrKv('src', $src);
                    $this_->getAttr('alt')->setAttrKv('alt', $alt);
                    $this_->getAttr('class')->addAttrsArray($classes);
                    $this_->getAttr('style')->importKv($style);

                })->render();
        }

        public static function video(string $src, array $classes = []): string
        {
            return DoubleTag::ins('video')->inner(function(DoubleTag $this_, array &$inner) use ($src, $classes) {
                $this_->addAttr('controls', RawAttr::class);
                $this_->getAttr('controls')->setAttrsString('controls');

                $this_->getAttr('src')->setAttrKv('src', $src);
                $this_->getAttr('class')->addAttrsArray($classes);

            })->render();
        }

        public static function audio(string $src, array $classes = []): string
        {
            return DoubleTag::ins('audio')->inner(function(DoubleTag $this_, array &$inner) use ($src, $classes) {
                $this_->addAttr('controls', RawAttr::class);
                $this_->getAttr('controls')->setAttrsString('controls');

                $this_->getAttr('src')->setAttrKv('src', $src);
                $this_->getAttr('class')->addAttrsArray($classes);

            })->render();
        }

        public static function br(): string
        {
            return SingleTag::ins('br')->render();
        }

        public static function hr(): string
        {
            return SingleTag::ins('hr')->inner(function(SingleTag $this_, array &$inner) {
                $this_->getAttr('class')->addAttrsArray([
                    "wp-block-separator",
                    "has-alpha-channel-opacity",
                ]);

            })->render();
        }
    }