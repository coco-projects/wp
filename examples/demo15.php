<?php

    require 'common.php';

    $manager->makePostPayRead(242, 1000);

/*
        $manager->makePostPayDownload(242, 1000, [
            [
                'link' => 'https://www.google.com',
                'more' => '资源1更多内容',
                'name' => '自定义按钮文案11',
            ],
            [
                'link' => 'https://www.sina.com',
            ],
        ]);
*/

//        $manager->makePostPayImage(242, 1000,[296,308],1);

    /*
    $manager->makePostPayVideo(242, 1000, [
        [
            'url'   => 'https://telegra.ph/file/6522c3c39190ee4c772ee.mp4',
            'pic'   => 'https://telegra.ph/file/695ef88e7da5f05d76a33.jpg',
            'title' => '第1集',
        ],
        [
            'url'   => 'https://telegra.ph/file/6522c3c39190ee4c772ee.mp4',
            'title' => '第2集',
        ],
        [
            'url' => 'https://telegra.ph/file/6522c3c39190ee4c772ee.mp4',
        ],

    ]);*/

//        $manager->setPostThumbnail(242, 296);