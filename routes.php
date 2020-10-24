<?php

use Jcc\Scanlogin\Models\Scan;

Route::any('wechat', function () {
    $app = app('wechat');

    $app->server->push(function ($message) {
        \Log::info($message);
        $msg = json_encode($message, JSON_UNESCAPED_UNICODE);
        switch ($message['MsgType']) {
            case 'event':
                return '收到事件消息' . $msg;
                break;
            case 'text':
                return '收到文字消息' . $msg;
                break;
            case 'image':
                return '收到图片消息' . $msg;
                break;
            case 'voice':
                return '收到语音消息' . $msg;
                break;
            case 'video':
                return '收到视频消息' . $msg;
                break;
            case 'location':
                return '收到坐标消息' . $msg;
                break;
            case 'link':
                return '收到链接消息' . $msg;
                break;
            case 'file':
                return '收到文件消息' . $msg;
            case 'subscribe':
                return '关注' . $msg;
            case 'unsubscribe':
                return '取关' . $msg;
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
    $app  = app('wechat');
    $scan = Scan::where('expired_at', '>', now())->where('is_uses', false)->first();
    if ($scan) {
        Scan::where('id', $scan->id)->where('is_used', 0)->update(['is_used' => 1]);
    } else {
        $uuid             = uniqid();
        $result           = $app->qrcode->temporary($uuid, 6 * 24 * 3600);
        $ticket           = $result['ticket'];
        $scan             = new Scan();
        $scan->uuid       = $uuid;
        $scan->ticket     = $ticket;
        $scan->expired_at = now()->addSeconds($result['expire_seconds']);
        $scan->save();
    }
    $ticket = $scan->ticket;
    $url    = $app->qrcode->url($ticket);
    return $url;
});


