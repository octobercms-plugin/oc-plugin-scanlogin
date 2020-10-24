<?php namespace Jcc\Scanlogin\Components;

use Cms\Classes\ComponentBase;
use Jcc\Scanlogin\Models\Scan;
use Jcc\Scanlogin\Models\Settings;
use Illuminate\Support\Facades\Cache;

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
        if (!in_array(post('login_type'), ['gongzhonghao', 'weixin', 'mini'])) {
            abort(404);
        }
        $uuid = uniqid('scan_login_');

        //todo 限流一个ip请求10次

        switch (post('login_type')) {
            case 'gongzhonghao':
                if (Scan::where('ip_address', request()->ip())
                        ->where('created_at', '>', date('Y-m-d'))->count() > 100) {//一天之内一个ip只能等录几次
                    return ['status' => 'error', 'msg' => 'one ip too many login', 'data' => []];
                }
                $app = app('wechat');
                //todo  一段时间内只生成这么多的二维码，uuid可以重用
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
                break;
            case 'mini':
                break;
        }
        Cache::put($uuid, $uuid, now()->addMinutes(5));
        return ['status' => 'success', 'msg' => 'ok', 'data' => ['uuid' => $uuid]];
    }
}
