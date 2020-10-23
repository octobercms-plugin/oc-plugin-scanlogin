<?php
Route::any('wechat', function () {
    $app = app('wechat');
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


