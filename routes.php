<?php


Route::any('wechat','Jcc\Scanlogin\Http\Controllers\ScanloginController@wechat');
Route::get('mini/login','Jcc\Scanlogin\Http\Controllers\ScanloginController@miniLogin');

