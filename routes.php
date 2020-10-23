<?php
Route::any('wechat', function () {
    $response = app('wechat')->server->serve();
    return $response;
});


