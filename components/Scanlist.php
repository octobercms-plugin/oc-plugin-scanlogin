<?php namespace Jcc\Scanlogin\Components;

use Cms\Classes\ComponentBase;
use Jcc\Scanlogin\Models\Scan;
use Jcc\Scanlogin\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Url;
use Endroid\QrCode\QrCode;
use System\Models\File;

class Scanlist extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'scanlist Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['scan_setting'] = new Settings();
    }

    public function onGetUuidByLoginType()
    {
        $loginType = post('login_type');
        if (!in_array(post('login_type'), ['gongzhonghao', 'weixin', 'mini'])) {
            abort(404);
        }
        $uuid = uniqid('scan_login_');

        if (Scan::where('ip_address', request()->ip())->where('created_at', '>', date('Y-m-d'))->count()
            > Settings::get('gongzhonghao_login_ip_login_count', 100)
        ) {//一天之内一个ip只能等录几次
            return [
                'status'   => 'error',
                'redirect' => Url::to('/'),
                'msg'      => 'one ip too many login',
                'data'     => []
            ];
        }

        switch (post('login_type')) {
            case 'gongzhonghao':

                $app = app('wechat');
                //todo  一段时间内只生成这么多的二维码，uuid可以重用,那么多二维码，也不用吧，用户有那么多嘛
                $result             = $app->qrcode->temporary($uuid, 3600);
                $ticket             = $result['ticket'];
                $scan               = new Scan();
                $scan->login_type   = Scan::LOGIN_TYPE_GONGZHONGHAO;
                $scan->uuid         = $uuid;
                $scan->ticket       = $ticket;
                $scan->is_use       = 0;
                $scan->start_use_at = now()->addMinutes(5);
                $scan->ip_address   = request()->ip();
                $scan->expired_at   = now()->addSeconds($result['expire_seconds']);
                $scan->save();
                break;
            case 'weixin':
                $qrCode = new QrCode(Url::to('wechat/redirect?uuid=' . $uuid));
                $model  = new File();
                $model->fromData($qrCode->writeString(), uniqid() . '.png');
                $model->is_public = true;
                $model->save();

//                $model->getPath()
                $scan               = new Scan();
                $scan->login_type   = Scan::LOGIN_TYPE_WEIXIN;
                $scan->uuid         = $uuid;
                $scan->is_use       = 0;
                $scan->start_use_at = now()->addMinutes(5);
                $scan->ip_address   = request()->ip();

                $scan->save();
                $scan->img()->save($model);


                break;
            case 'mini':
                $file = $this->getMiniQRCode('pages/post/login/login?uuid='.$uuid,430);
                $model = new File();
                $model->fromData(strval($file->getBody()), uniqid() . '.png');
                $model->is_public = true;
                $model->save();
                $scan               = new Scan();
                $scan->login_type   = Scan::LOGIN_TYPE_MINI;
                $scan->uuid         = $uuid;
                $scan->is_use       = 0;
                $scan->start_use_at = now()->addMinutes(5);
                $scan->ip_address   = request()->ip();

                $scan->save();
                $scan->img()->save($model);


                break;
        }
        Cache::put($uuid, $uuid, now()->addMinutes(10));


        return [
            'status'   => 'success',
            'redirect' => Url::to('/scan_login?login_type=' . $loginType . '&uuid=' . $uuid),
            'msg'      => 'ok',
            'data'     => ['uuid' => $uuid]
        ];
    }

    protected function getMiniQRCode($path1, $width)
    {

        $path1       = urldecode($path1);
        $miniProgram = app('mini');
//        dd($miniProgram);

        if (str_contains($path1, '?')) {

            $pathArray = explode('?', $path1);
            $scene     = $pathArray[1];
            $path      = $pathArray[0];
            if (strlen($scene) >= 32) {
                //参数大于32生成有限制数量的二维码
                $file = $miniProgram->app_code->get($path1, ['width' => $width]);
            } else {
                //参数小于32生成无限制数量的二维码
                $file = $miniProgram->app_code->getUnlimit($scene, ['page' => $path, 'width' => $width]);
            }
        } else {
            $file = $miniProgram->app_code->getUnlimit('noparames', ['page' => $path1, 'width' => $width]);
        }
        return $file;
    }

}
