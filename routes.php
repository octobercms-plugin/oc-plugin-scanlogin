<?php
Route::any('wechat', function () {
    $app = app('wechat');
    $app->server->push(function ($message) {
        return "您好！欢迎使用 EasyWeChat!";
    });
    $response = $app->server->serve();
    return $response->send();
});


