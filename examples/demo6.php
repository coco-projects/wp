<?php

    use Coco\htmlBuilder\dom\DoubleTag;
    use Coco\wp\ArticleContent;
    use Coco\wp\Tag;

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

    echo ArticleContent::paragraph([

        Tag::p([
            '你你你',

            Tag::a('https://baidu.com', 'a标签'),
            PHP_EOL,

            Tag::a('https://baidu.com'),
            PHP_EOL,
        ]),
    ]);
    echo PHP_EOL;

    echo ArticleContent::image([
        Tag::figure([
            Tag::img('http://dev6080/wp-content/uploads/2025/03/牛牛宝.png'),
        ], [
            'wp-block-image',
            'aligncenter',
        ]),
    ]);
    echo PHP_EOL;

    echo ArticleContent::image([
        Tag::figure([
            Tag::img('http://dev6080/wp-content/uploads/2025/03/牛牛宝.png'),
        ], [
            'wp-block-image',
            'aligncenter',
        ]),
    ]);
    echo PHP_EOL;

    echo ArticleContent::image([
        Tag::figure([

            Tag::a('https://baidu.com', Tag::img('http://dev6080/wp-content/uploads/2025/03/牛牛宝.png')),
            Tag::figcaption('妙妙妙', ['wp-element-caption']),

        ], [
            'wp-block-image',
            'aligncenter',
        ]),
    ]);
    echo PHP_EOL;


