<?php

    namespace Coco\wp;

    use Coco\htmlBuilder\attrs\RawAttr;
    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\htmlBuilder\dom\SingleTag;
    use Coco\htmlBuilder\dom\tags\Div;

    class Tag
    {
        public static function button(string $link, string $text = '', string $target = "_blank"): string
        {
            return Div::ins()->inner(function(DoubleTag $this_, array &$inner) use ($text, $link, $target) {
                $this_->getAttr('class')->addAttrsArray([
                    'wp-block-button',
                    'has-custom-font-size',
                    'is-style-fill',
                ]);

                $this_->getAttr('style')->importKv([
                    "font-size" => "14px",
                ]);

                $inner[] = DoubleTag::ins('a')
                    ->inner(function(DoubleTag $this_, array &$inner) use ($text, $link, $target) {
                        $this_->getAttr('href')->setAttrKv('href', $link);
                        $this_->getAttr('target')->setAttrKv('target', $target);

                        $this_->getAttr('class')->addAttrsArray([
                            'wp-block-button__link',
                            'has-luminous-vivid-orange-background-color',
                            'has-background',
                            'wp-element-button',
                            '',
                        ]);

                        $this_->getAttr('style')->importKv([
                            "border-radius" => "10px",
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

        public static function a(string $link, string $text = '', string $target = "_blank"): string
        {
            !$text && $text = $link;

            return DoubleTag::ins('a')->inner(function(DoubleTag $this_, array &$inner) use ($text, $link, $target) {
                $this_->getAttr('href')->setAttrKv('href', $link);
                $this_->getAttr('target')->setAttrKv('target', $target);

                $inner[] = $text;
            })->render();
        }


        public static function p(mixed $content, array $classes = []): string
        {
            return DoubleTag::ins('p')->inner(function(DoubleTag $this_, array &$inner) use ($content, $classes) {
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

        public static function img(string $src, string $alt = '', array $classes = []): string
        {
            return SingleTag::ins('img')->inner(function(SingleTag $this_, array &$inner) use ($src, $alt, $classes) {
                $this_->getAttr('src')->setAttrKv('src', $src);
                $this_->getAttr('alt')->setAttrKv('alt', $alt);
                $this_->getAttr('class')->addAttrsArray($classes);
                $this_->getAttr('style')->importKv([
                    "object-fit" => "cover",
                ]);

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
    }