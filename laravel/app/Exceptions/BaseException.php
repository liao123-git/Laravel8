<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class BaseException extends Exception
{
    //
    public function __construct(array $codeResponse, $info = '')
    {
        // 使用方式 throw new BaseException($codeResponse, $info);
        // $codeResponse 就是一些公共的报错码和报错信息，在 app 目录下面
        list($code, $message) = $codeResponse;
        parent::__construct($info ?: $message, $code);
    }
}
