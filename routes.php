<?php

Route::any('wechat', function ()  {
    $app = app('wechat');

    $app->server->push(function ($message) {
        \Log::info($message);
        $msg = json_encode($message,JSON_UNESCAPED_UNICODE);
        switch ($message['MsgType']) {
            case 'event':
                return '收到事件消息'.$msg;
                break;
            case 'text':
                return '收到文字消息'.$msg;
                break;
            case 'image':
                return '收到图片消息'.$msg;
                break;
            case 'voice':
                return '收到语音消息'.$msg;
                break;
            case 'video':
                return '收到视频消息'.$msg;
                break;
            case 'location':
                return '收到坐标消息'.$msg;
                break;
            case 'link':
                return '收到链接消息'.$msg;
                break;
            case 'file':
                return '收到文件消息'.$msg;
            case 'subscribe':
                return '关注'.$msg;
            case 'unsubscribe':
                return '取关'.$msg;
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

Route::get('qrcode_url', function () {
    $app = app('wechat');
    $result = $app->qrcode->temporary('foo', 6 * 24 * 3600);
    $url = $app->qrcode->url($result['ticket']);
    return $url;
});


