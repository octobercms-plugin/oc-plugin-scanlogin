<?php
$app = app('wechat');

Route::any('wechat', function () use ($app) {
    $app->server->push(function ($message) {
        \Log::info($message);
        switch ($message['MsgType']) {
            case 'event':
                return '收到事件消息';
                break;
            case 'text':
                return '收到文字消息';
                break;
            case 'image':
                return '收到图片消息';
                break;
            case 'voice':
                return '收到语音消息';
                break;
            case 'video':
                return '收到视频消息';
                break;
            case 'location':
                return '收到坐标消息';
                break;
            case 'link':
                return '收到链接消息';
                break;
            case 'file':
                return '收到文件消息';
            case 'subscribe':
                return '关注';
            case 'unsubscribe':
                return '取关';
            // ... 其它消息
            default:
                return '收到其它消息';
                break;
        }

        // ...
    });
    $response = $app->server->serve();
    return $response->send();
});

Route::get('qrcode_url', function ()use($app) {
    $result = $app->qrcode->temporary('foo', 6 * 24 * 3600);
    $url = $app->qrcode->url($result['ticket']);
    return $url;
});


