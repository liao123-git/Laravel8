<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class BaseException extends Exception
{
    //
    public function __construct(array $codeResponse, $info = '')
    {
        list($code, $message) = $codeResponse;
        parent::__construct($info ?: $message, $code);
    }
}
