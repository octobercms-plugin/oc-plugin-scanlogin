<?php namespace Jcc\Scanlogin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Jcc\Scanlogin\Models\Scan;
use Illuminate\Support\Facades\Cache;
use RainLab\User\Models\User;
use Auth;

class ScanloginController extends BaseController
{

    public function wechat()
    {
        $app = app('wechat');

        $app->server->push(function ($message) {
            \Log::info($message);
            $msg = json_encode($message, JSON_UNESCAPED_UNICODE);
            switch ($message['MsgType']) {
                case 'event':

                    if ($message['Event'] == 'subscribe') {
                        if ($message['EventKey'] ?? false) {
                            $key = substr($message['EventKey'], 8);
                            if (Cache::has($key)) {
                                $password = strtolower(str_random());
                                $user     = User::where('openid', $message['FromUserName'])->first();
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
                                    $user->openid = $message['FromUserName'];
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
                    if ($message['Event'] == 'SCAN') {
                        if ($message['EventKey'] ?? false) {
                            $key = $message['EventKey'];
                            if (Cache::has($key)) {
                                $password = strtolower(str_random());
                                $user     = User::where('openid', $message['FromUserName'])->first();
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
                                    $user->openid = $message['FromUserName'];
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


                    return '收到事件消息' . $msg;
                    break;
                case 'text':
                    return '收到文字消息' . $msg;
                    break;
                case 'image':
                    return '收到图片消息' . $msg;
                    break;
                case 'voice':
                    return '收到语音消息' . $msg;
                    break;
                case 'video':
                    return '收到视频消息' . $msg;
                    break;
                case 'location':
                    return '收到坐标消息' . $msg;
                    break;
                case 'link':
                    return '收到链接消息' . $msg;
                    break;
                case 'file':
                    return '收到文件消息' . $msg;
                case 'subscribe':
                    return '关注' . $msg;
                case 'unsubscribe':
                    return '取关' . $msg;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });
        $response = $app->server->serve();
        return $response->send();
    }

    public function middle()
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
