<?php namespace Jcc\Scanlogin\Components;

use Cms\Classes\ComponentBase;
use Url;

class WechatRedirect extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'WechatRedirect Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $app = app('wechat');
        return $app->oauth->scopes(['snsapi_base'])
            ->setRequest(request())
            ->redirect(Url::to('/wechat/callback'));
    }
}
