<?php

    require 'common.php';
    $payConfig = 'a:27:{s:8:"pay_type";s:1:"6";s:9:"pay_limit";s:1:"2";s:8:"pay_modo";s:1:"0";s:12:"points_price";s:0:"";s:12:"vip_1_points";s:0:"";s:12:"vip_2_points";s:0:"";s:9:"pay_price";s:4:"1111";s:18:"pay_original_price";s:4:"2222";s:13:"promotion_tag";s:45:"<i class="fa fa-fw fa-bolt"></i> 限时特惠";s:11:"vip_1_price";s:4:"3333";s:11:"vip_2_price";s:4:"4444";s:19:"pay_rebate_discount";s:3:"555";s:9:"pay_cuont";s:3:"200";s:11:"pay_gallery";s:7:"296,308";s:16:"pay_gallery_show";s:1:"1";s:9:"video_url";s:13:"视频资源1";s:9:"video_pic";s:0:"";s:11:"video_title";s:7:"di 1 ji";s:13:"video_episode";a:1:{i:0;a:2:{s:5:"title";s:7:"di 2 ji";s:3:"url";s:12:"视频资源";}}s:18:"video_scale_height";s:1:"0";s:12:"pay_download";a:2:{i:0;a:4:{s:4:"link";s:7:"资源1";s:4:"more";s:19:"资源1更多内容";s:4:"icon";s:14:"fa fa-download";s:4:"name";s:23:"自定义按钮文案11";}i:1;a:4:{s:4:"link";s:7:"资源2";s:4:"more";s:19:"资源2更多内容";s:4:"icon";s:14:"fa fa-download";s:4:"name";s:0:"";}}s:10:"attributes";a:2:{i:0;a:2:{s:3:"key";s:14:"属性名称11";s:5:"value";s:14:"属性内容11";}i:1;a:2:{s:3:"key";s:14:"属性名称22";s:5:"value";s:14:"属性内容22";}}s:9:"demo_link";a:3:{s:3:"url";s:18:"http://deom_urlurl";s:4:"text";s:17:"deom_urlurl--text";s:6:"target";s:6:"_blank";}s:9:"pay_title";s:14:"商品标题--";s:7:"pay_doc";s:14:"商品简介--";s:11:"pay_details";s:14:"更多详情--";s:14:"pay_extra_hide";s:0:"";}';
    $payConfig = 'a:27:{s:8:"pay_type";s:1:"1";s:9:"pay_limit";s:1:"2";s:8:"pay_modo";s:1:"0";s:12:"points_price";s:0:"";s:12:"vip_1_points";s:0:"";s:12:"vip_2_points";s:0:"";s:9:"pay_price";s:4:"1111";s:18:"pay_original_price";s:4:"2222";s:13:"promotion_tag";s:45:"<i class="fa fa-fw fa-bolt"></i> 限时特惠";s:11:"vip_1_price";s:4:"3333";s:11:"vip_2_price";s:4:"4444";s:19:"pay_rebate_discount";s:3:"555";s:9:"pay_cuont";s:3:"200";s:11:"pay_gallery";s:7:"296,308";s:16:"pay_gallery_show";s:1:"1";s:9:"video_url";s:13:"视频资源1";s:9:"video_pic";s:0:"";s:11:"video_title";s:7:"di 1 ji";s:13:"video_episode";a:1:{i:0;a:2:{s:5:"title";s:7:"di 2 ji";s:3:"url";s:12:"视频资源";}}s:18:"video_scale_height";s:1:"0";s:12:"pay_download";a:2:{i:0;a:4:{s:4:"link";s:7:"资源1";s:4:"more";s:19:"资源1更多内容";s:4:"icon";s:14:"fa fa-download";s:4:"name";s:23:"自定义按钮文案11";}i:1;a:4:{s:4:"link";s:7:"资源2";s:4:"more";s:19:"资源2更多内容";s:4:"icon";s:14:"fa fa-download";s:4:"name";s:0:"";}}s:10:"attributes";a:2:{i:0;a:2:{s:3:"key";s:14:"属性名称11";s:5:"value";s:14:"属性内容11";}i:1;a:2:{s:3:"key";s:14:"属性名称22";s:5:"value";s:14:"属性内容22";}}s:9:"demo_link";a:3:{s:3:"url";s:18:"http://deom_urlurl";s:4:"text";s:17:"deom_urlurl--text";s:6:"target";s:6:"_blank";}s:9:"pay_title";s:14:"商品标题--";s:7:"pay_doc";s:14:"商品简介--";s:11:"pay_details";s:14:"更多详情--";s:14:"pay_extra_hide";s:0:"";}';

    var_export(unserialize($payConfig));

    $a = [
        'pay_type'            => '6',
        'pay_limit'           => '2',
        'pay_modo'            => '0',
        'points_price'        => '',
        'vip_1_points'        => '',
        'vip_2_points'        => '',
        'pay_price'           => '1111',
        'pay_original_price'  => '2222',
        'promotion_tag'       => '<i class="fa fa-fw fa-bolt"></i> 限时特惠',
        'vip_1_price'         => '3333',
        'vip_2_price'         => '4444',
        'pay_rebate_discount' => '555',
        'pay_cuont'           => '200',
        'pay_gallery'         => '296,308',
        'pay_gallery_show'    => '1',


        'video_url'           => '视频资源1',
        'video_pic'           => '',
        'video_title'         => 'di 1 ji',
        'video_episode'       => [
            0 => [
                'title' => 'di 2 ji',
                'url'   => '视频资源',
            ],
        ],
        'video_scale_height'  => '0',



        'pay_download'        => [
            0 => [
                'link' => '资源1',
                'more' => '资源1更多内容',
                'icon' => 'fa fa-download',
                'name' => '自定义按钮文案11',
            ],
            1 => [
                'link' => '资源2',
                'more' => '资源2更多内容',
                'icon' => 'fa fa-download',
                'name' => '',
            ],
        ],
        'attributes'          => [
            0 => [
                'key'   => '属性名称11',
                'value' => '属性内容11',
            ],
            1 => [
                'key'   => '属性名称22',
                'value' => '属性内容22',
            ],
        ],
        'demo_link'           => [
            'url'    => 'http://deom_urlurl',
            'text'   => 'deom_urlurl--text',
            'target' => '_blank',
        ],
        'pay_title'           => '商品标题--',
        'pay_doc'             => '商品简介--',
        'pay_details'         => '更多详情--',
        'pay_extra_hide'      => '',
    ];
