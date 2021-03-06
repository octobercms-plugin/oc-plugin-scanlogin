<?php return [
    'plugin' => [
        'name' => '扫码登录',
        'description' => '',
    ],
    'scanlogin' => [
        'gongzhonghao_label' => '公众号关注扫码登录',
        'gongzhonghao_comment' => '支持公众号关注登录',
        'gongzhonghao_ip_login_label' => 'ip限制',
        'gongzhonghao_ip_login_comment' => '一个ip一天之内限制登录几次，会消耗公众号二维码的数量',
        'weixin_login_label' => '微信扫码登录',
        'weixin_login_comment' => '支持微信扫码登录',
        'mini_login_label' => '微信小程序扫码登录',
        'mini_login_comment' => '支持微信小程序扫码登录',
        'tab_login' => '登录配置',
        'menu_label' => '微信扫码登录',
        'app_id_label' => 'app_id',
        'app_id_comment' => '微信app_id',
        'token_label' => 'token',
        'token_comment' => '微信token',
        'tab_weixin' => '微信配置',
        'secret_label' => 'secret',
        'secret_comment' => '微信secret',
        'settings_description'=>'微信扫码登录相关配置'
    ],
    'scanlogin_mini' => [
        'app_id_label' => 'app_id',
        'app_id_comment' => '小程序app_id',
        'tab_mini' => '小程序配置',
        'secret_label' => '小程序secret',
        'secret_comment' => '小程序secret',
    ],
];
