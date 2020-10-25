<?php


Route::any('wechat','Jcc\Scanlogin\Http\Controllers\ScanloginController@wechat');
Route::post('mini/login','Jcc\Scanlogin\Http\Controllers\ScanloginController@miniLogin');

