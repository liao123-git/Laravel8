<?php

namespace App\Http\Controllers\WX;

use App\CodeResponse;
use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\VerifyRequestInput;

class WXController extends Controller
{
    use VerifyRequestInput;

    protected $only;
    protected $except;

    public function __construct()
    {
        $option = [];
        if (!is_null($this->only)) {
            $option['only'] = $this->only;
        }
        if (!is_null($this->except)) {
            $option['except'] = $this->except;
        }

        $this->middleware("auth:wx", $option);
        // 在需要用到的控制器中写入 protected $only = [需要登录之后才能使用的方法]; 或 protected $except = [无需登录就能使用的方法];
        // 看哪个方便用哪个，不能同时写两个，会被覆盖
    }

    protected function codeReturn($data = null)
    {
        list($errno, $errmsg) = CodeResponse::SUCCESS;
        $res = ['errno' => $errno, 'errmsg' => $errmsg];
        $data && $res['data'] = $data;
        return response()->json($res);
    }

    protected function fail($codeResponse, $info = false)
    {
        throw new BaseException($codeResponse, $info);
    }
}
