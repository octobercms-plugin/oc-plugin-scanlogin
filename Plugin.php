<?php namespace Jcc\Scanlogin;

use System\Classes\PluginBase;
use EasyWeChat\Factory;
use Jcc\Scanlogin\Models\Settings;

class Plugin extends PluginBase
{
    public function register()
    {
    }
    public function boot()
    {
        $config = [
            'app_id'        => Settings::get('app_id'),
            'secret'        => Settings::get('secret'),
            'token'         => Settings::get('token'),
            'response_type' => 'collection',

        ];
        $this->app->singleton('wechat', function () use ($config) {
            return Factory::officialAccount($config);
        });
        $miniConfig = [
            'app_id'        => Settings::get('mini_app_id'),
            'secret'        => Settings::get('mini_secret'),
            'response_type' => 'collection',

        ];
        $this->app->singleton('wechat', function () use ($config) {
            return Factory::officialAccount($config);
        });

        $this->app->singleton('mini', function () use ($miniConfig) {
            return Factory::miniProgram($miniConfig);
        });
    }

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
        return [
            'scanlogin' => [
                'label'       => 'jcc.scanlogin::lang.scanlogin.menu_label',
                'description' => 'jcc.scanlogin::lang.scanlogin.settings_description',
                'category'    => 'jcc.scanlogin::lang.scanlogin.menu_label',
                'icon'        => 'icon-pencil',
                'class'       => 'Jcc\Scanlogin\Models\Settings',
                'order'       => 500,
                'keywords'    => 'scan login',
                'permissions' => ['jcc.scanlogin.manage_settings']
            ]
        ];
    }
}
