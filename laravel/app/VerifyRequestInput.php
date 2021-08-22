<?php
namespace App;

use App\Exceptions\BaseException;
use Illuminate\Validation\Rule;

trait VerifyRequestInput{
    /**
     * @param $key
     * @param $default
     * @param $rule
     * @return mixed
     * @throws BaseException
     */
    public function verifyData($key, $default, $rule)
    {
        $value = \request()->input($key, $default);

        $validator = \Illuminate\Support\Facades\Validator::make([$key => $value], [$key => $rule]);

        if ($validator->fails()) {
            throw new BaseException(CodeResponse::PARAM_VALUE_ILLEGAL);
        }

        return $value;
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws BaseException
     */
    public function verifyString($key, $default = null)
    {
        return $this->verifyData($key, $default, "required|string");
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @throws BaseException
     */
    public function verifyInteger($key, $default = null)
    {
        return $this->verifyData($key, $default, "integer");
    }

    /**
     * @param $key
     * @param $default
     * @param $enum
     * @return mixed
     * @throws BaseException
     */
    public function verifyEnum($key, $default, $enum)
    {
        return $this->verifyData($key, $default, Rule::in($enum));
    }
}
