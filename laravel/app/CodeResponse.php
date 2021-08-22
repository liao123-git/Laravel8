<?php


namespace App;


class CodeResponse
{
    // 通用返回码
    const SUCCESS = [0, 'success'];
    const FAIL = [300, 'fail'];
    const LOGIN_FAIL = [300, 'username or password wrong'];
    const UNLOGIN = [501, 'please login first'];
    const PARAM_VALUE_ILLEGAL = [422, 'param value illegal'];

    // 业务返回码
}
