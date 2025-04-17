<?php

    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\wp\ArticleContent;
    use Coco\wp\Tag;
    use Coco\wp\WpTag;

    require 'common.php';

    $telegraphImg1 = 'https://telegra.ph/file/1310a205f732f9bae8141.jpg';
    $telegraphImg2 = 'https://telegra.ph/file/10bb4bf90be7fd67cad77.jpg';
    $telegraphImg3 = 'https://telegra.ph/file/5cd2083b75dc86a624eed.jpg';
    $telegraphImg4 = 'https://telegra.ph/file/695ef88e7da5f05d76a33.jpg';

    $telegraphvideo1 = 'https://telegra.ph/file/6522c3c39190ee4c772ee.mp4';

    $telegraphAudio1 = 'https://webfs.tx.kugou.com/202407241149/c4eb3ef1e32231d763159b071da75352/v3/44226fcc3e842fb94684e80b0449505f/yp/p_0_960175/ap1014_us0_mif68d61cbb99e1ef8dec2c7fdc941cef4_pi406_mx106793394_s3602779325.mp3';

    $telegraphYoutube = 'https://www.youtube.com/watch?v=FzG4uDgje3M';
    $telegraphVimeo   = 'https://vimeo.com/340057344';

    $telegraphTwitter = 'https://twitter.com/elonmusk/status/1815929451256979636';

    echo WpTag::h1('h1 标签');
    echo PHP_EOL;
    echo WpTag::h2('h2 标签');
    echo PHP_EOL;
    echo WpTag::h3('h3 标签');
    echo PHP_EOL;
    echo WpTag::h4('h4 标签');
    echo PHP_EOL;
    echo WpTag::h5('h5 标签');
    echo PHP_EOL;
    echo WpTag::h6('h6 标签');
    echo PHP_EOL;

    echo WpTag::imageWithLink($telegraphImg1, $telegraphvideo1, '喵喵喵');
    echo PHP_EOL;

    echo WpTag::hr();
    echo PHP_EOL;

    echo WpTag::aBlock($telegraphvideo1, 'a 按钮', 'red');
    echo PHP_EOL;

    echo WpTag::p('一个段落', 'blue');
    echo PHP_EOL;

    echo WpTag::p([
        '一个段落,',
        Tag::a('https://baidu.com', 'a标签'),
    ]);
    echo PHP_EOL;

    echo WpTag::audio($telegraphAudio1);
    echo PHP_EOL;

    echo WpTag::video($telegraphvideo1);
    echo PHP_EOL;

    echo WpTag::dPlayer($telegraphvideo1);
    echo PHP_EOL;

    echo WpTag:: listQuote([
        '图片描述11',
        '图片描述22',
        '图片描述33',
    ]);
    echo PHP_EOL;

    echo WpTag:: list([
        '图片描述11',
        '图片描述22',
        '图片描述33',
    ], 'red', '16px');
    echo PHP_EOL;

    echo WpTag:: hideContent(WpTag::listQuote([
        '图片描述11',
        '图片描述22',
        '图片描述33',
    ]));
    echo PHP_EOL;

    echo WpTag:: buttons([
        [
            "link"         => 'https://baidu.com',
            "text"         => "按钮111",
            "target"       => '_self',
            "textColor"    => '#ff0000',
            "textBgColor"  => '#0000ff',
            "fontSize"     => '20px',
            "borderRadius" => '18px',
        ],
        [
            "link"     => 'https://baidu.com',
            "text"     => "按钮222",
            "btnColor" => "green",
        ],
        [
            "link"     => 'https://baidu.com',
            "text"     => "按钮333",
            "btnColor" => "red",
        ],
    ]);
    echo PHP_EOL;

    echo WpTag:: gallery([
        [
            "src"     => $telegraphImg1,
            "caption" => "图片描述",
        ],
        [
            "src"     => $telegraphImg1,
            "caption" => "图片描述",
        ],
        [
            "src" => $telegraphImg2,
        ],
    ]);
    echo PHP_EOL;

    echo WpTag::zibllTabs([
        [
            "title"   => "标签11",
            "content" => WpTag:: list([
                '图片描述11',
                '图片描述22',
                '图片描述33',
            ], 'green'),
        ],
        [
            "title"   => "标签22",
            "content" => WpTag:: listQuote([
                '图片描述11',
                '图片描述22',
                '图片描述33',
            ]),
        ],

        [
            "title"   => "标签11",
            "content" => WpTag::p('111'),

        ],

        [
            "title"   => "标签22",
            "content" => WpTag::p('222'),

        ],

        [
            "title"   => "标签33",
            "content" => WpTag::p('333'),

        ],

    ], 1);

    echo WpTag:: details('【点击查看详细介绍】', [
        WpTag::p('333'),

        WpTag:: buttons([
            [
                "link"     => 'https://baidu.com',
                "text"     => "按钮111",
                "btnColor" => "orange",
            ],
            [
                "link" => 'https://baidu.com',
                "text" => "按钮222",
            ],
        ]),

    ], !true, 'red', '16px');
    echo PHP_EOL;

    echo WpTag:: zibllDetails('【点击查看详细介绍】', [
        WpTag::p('333'),

        WpTag:: buttons([
            [
                "link"     => 'https://baidu.com',
                "text"     => "按钮111",
                "btnColor" => "orange",
            ],
            [
                "link" => 'https://baidu.com',
                "text" => "按钮222",
            ],
        ]),

    ], !true);

    echo PHP_EOL;

    echo WpTag::groupConstrained([
        WpTag::p('333'),
        WpTag::p('444'),
        WpTag::p('555'),
    ]);
    echo PHP_EOL;
    echo WpTag::groupFlexHorizontal([
        WpTag::p('333'),
        WpTag::p('444'),
        WpTag::p('555'),

    ], true, 'center');
    echo PHP_EOL;

    echo WpTag::groupFlexVertical([
        WpTag::p('333'),
        WpTag::p('444'),
        WpTag::p('555'),

    ], false, 'center');
    echo PHP_EOL;

    echo WpTag::groupGrid([
        WpTag::p('111'),
        WpTag::p('222'),
        WpTag::p('333'),
        WpTag::p('444'),
        WpTag::p('555'),
        WpTag::p('666'),

    ], null, 16);
    echo PHP_EOL;

    echo WpTag::image($telegraphImg2, 190);
    echo PHP_EOL;

    echo WpTag::image($telegraphImg2, 0, 190, '2/3', 'contain');

    echo PHP_EOL;
    echo WpTag::image($telegraphImg2, 210, 0, 'auto', 'cover');
    echo PHP_EOL;

    echo WpTag::image($telegraphImg2, 0, 0, 'auto', 'contain');
    echo PHP_EOL;

    echo WpTag::columnsAvg([
        WpTag::p('111'),
        WpTag::p('222'),
        WpTag::p('333'),
        WpTag::p('666'),

    ]);
    echo PHP_EOL;

    echo WpTag::columns([
        [
            "content" => WpTag::p('111'),
            "width"   => "25%",
        ],
        [
            "content" => WpTag::p('222'),
            "width"   => "25%",
        ],
        [
            "content" => WpTag::p('333'),
        ],
    ]);
    echo PHP_EOL;

    echo WpTag::columns([
        [
            "content" => WpTag::p('111'),
            "width"   => "120px",
        ],
        [
            "content" => WpTag::p('222'),
            "width"   => "250px",
        ],
        [
            "content" => WpTag::p('333'),
        ],
    ]);
    echo PHP_EOL;

    echo WpTag::tagCloud();
    echo PHP_EOL;
