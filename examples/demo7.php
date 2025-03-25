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
    $telegraphvideo2 = 'https://telegra.ph/file/df0cb8a21dc2022a40bfe.mp4';

    $telegraphAudio1 = 'https://webfs.tx.kugou.com/202407241149/c4eb3ef1e32231d763159b071da75352/v3/44226fcc3e842fb94684e80b0449505f/yp/p_0_960175/ap1014_us0_mif68d61cbb99e1ef8dec2c7fdc941cef4_pi406_mx106793394_s3602779325.mp3';

    $telegraphYoutube = 'https://www.youtube.com/watch?v=FzG4uDgje3M';
    $telegraphVimeo   = 'https://vimeo.com/340057344';

    $telegraphTwitter = 'https://twitter.com/elonmusk/status/1815929451256979636';

    echo WpTag::imageWithLink($telegraphImg1, $telegraphvideo1, '喵喵喵');
    echo PHP_EOL;

    echo WpTag::hr();
    echo PHP_EOL;

    echo WpTag::aBlock($telegraphvideo1, 'a 按钮');
    echo PHP_EOL;

    echo WpTag::p('一个段落');
    echo PHP_EOL;

    echo WpTag::p([
        '一个段落,',
        Tag::a('https://baidu.com', 'a标签'),
    ]);
    echo PHP_EOL;

    echo WpTag::image($telegraphImg2);
    echo PHP_EOL;

    echo WpTag::video($telegraphvideo1);
    echo PHP_EOL;

    echo WpTag::audio($telegraphAudio1);
    echo PHP_EOL;

    echo WpTag::easyVideoPlayer($telegraphvideo2);
    echo PHP_EOL;

    echo WpTag:: buttons([
        [
            "link" => 'https://baidu.com',
            "text" => "按钮111",
        ],
        [
            "link" => 'https://baidu.com',
            "text" => "按钮222",
        ],
    ]);
    echo PHP_EOL;




