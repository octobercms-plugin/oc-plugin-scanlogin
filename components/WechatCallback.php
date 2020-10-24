<?php namespace Jcc\Scanlogin\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cache;
use Jcc\Scanlogin\Models\Scan;
use RainLab\User\Models\User;

class WechatCallback extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'wechat Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $app      = app('wechat');
        $user     = $app->oauth->setRequest(request())->user();
        $original = $user->getOriginal();

        $array = array_only($original, array(
            'openid',
        ));

        $openId = $array['openid'];
        $key    = request()->uuid;

        \Log::info($key);
        if (Cache::has($key)) {
            $user     = User::where('openid', $openId)->first();
            $password = strtolower(str_random());
            if (!$user) {
                $user         = Auth::register(
                    [
                        'email'                 => uniqid() . '@sso.com',
                        'phone'                 => rand(),
                        'password'              => $password,
                        'password_confirmation' => $password,
                    ],
                    true
                );
                $user->openid = $openId;
                $user->save();
            }

            $user->scan_key = $key;
            $user->save();
            $scan = Scan::where('uuid', $key)->first();
            if ($scan) {
                $scan->user_id = $user->id;
                $scan->save();
            }
            Cache::put($key . 'login_state', 'confirm', 10);
        }


    }
}
