<?php namespace Jcc\Scanlogin\Components;

use Cms\Classes\ComponentBase;
use Jcc\Scanlogin\Models\Scan as ScanModel;
use Illuminate\Support\Facades\Cache;
use Url;

class Scan extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'scan Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $login_type = request()->login_type;
        $uuid       = request()->uuid;
        if (!in_array($login_type, array_keys(ScanModel::$loginTypeMaps))) {
            return ['status' => 'error', 'msg' => '参数错误', 'data' => []];
        }
        if (!Cache::has($uuid)) {
            return ['status' => 'error', 'msg' => '登录已过期', 'data' => []];
        }

        switch ($login_type) {
            case ScanModel::LOGIN_TYPE_GONGZHONGHAO:
                $app = app('wechat');

                $scan = ScanModel::where('uuid', $uuid)->where('is_use', 0)->first();
                if (!$scan) {
                    return ['status' => 'error', 'msg' => '参数失效', 'data' => []];
                }
                $this->page['qrcode_url']       = $app->qrcode->url($scan->ticket);//todo 隐藏掉真实地址
                $this->page['login_state_desc'] = '请扫码';
                $this->page['login_comment']    = '请关注公众号即可登录';
                $this->page['login_uuid']       = $uuid;
                $this->page['login_type']       = $login_type;
                break;
            case ScanModel::LOGIN_TYPE_WEIXIN:
                break;
            case ScanModel::LOGIN_TYPE_MINI:
                break;
        }
//        return ['status' => 'success', 'msg' => 'ok', 'data' => []];
    }

    public function onGetLoginState()
    {

        $uuid       = post('uuid');
        $login_type = post('login_type');
        if (!in_array($login_type, array_keys(ScanModel::$loginTypeMaps))) {
            return ['status' => 'error', 'msg' => '参数错误', 'data' => []];
        }
        $login_state_desc = '请扫码';
        $redirect         = '';
        if (Cache::has($uuid . 'login_state')) {
            $login_state = Cache::get($uuid . 'login_state');
            switch ($login_state) {
                case 'scan':
                    $login_state_desc = '已扫描';
                    break;
                case 'cancel':
                    $login_state_desc = '已取消';
                    break;
                case 'confirm':
                    $redirect         = Url::to('/middle?uuid='.$uuid);
                    $login_state_desc = '已确认登录';
                    break;
            }
        }
        return ['status'   => 'success', 'msg' => 'ok',
                'redirect' => $redirect, 'data' => ['login_state_desc' => $login_state_desc]];
    }
}
