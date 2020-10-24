<?php namespace Jcc\Scanlogin\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cache;
use RainLab\User\Models\User;
use Auth;

class Middle extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'middle Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $uuid = request()->uuid;
        if (!Cache::has($uuid)) {
            abort(404);
        }
        if (!Cache::has($uuid . 'login_state')) {
            abort(404);
        }
        if (Cache::get($uuid . 'login_state') != 'confirm') {
            abort(404);
        }
        $user = User::where('scan_key', $uuid)->first();
        if (!$user) {
            abort(404, 'code 失效');
        }
        Cache::forget($uuid);
        Auth::login($user);

        return redirect()->to('/');
    }
}
