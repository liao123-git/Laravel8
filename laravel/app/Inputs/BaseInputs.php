<?php


namespace App\Inputs;

use App\CodeResponse;
use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Validator;

class BaseInputs
{
    use \App\VerifyRequestInput;

    /**
     * @param null $data
     * @return $this
     * @throws BaseException
     */
    public function fill($data = null)
    {
        is_null($data) && $data = request()->input();

        $validator = Validator::make($data, $this->rules());
        if ($validator->fails()) {
            throw new BaseException(CodeResponse::PARAM_VALUE_ILLEGAL);
        }

        $map = get_object_vars($this);
        $keys = array_keys($map);

        collect($data)->map(function ($v, $k) use ($keys) {
            in_array($k, $keys) && $this->$k = $v;
        });

        return $this;
    }

    public function rules()
    {
    }

    /**
     * @param null $data
     * @return BaseInputs|static
     * @throws BaseException
     */
    public static function new($data = null)
    {
        return (new static())->fill($data);
    }
}
