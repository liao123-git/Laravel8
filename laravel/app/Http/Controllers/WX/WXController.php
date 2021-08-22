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
