<?php


namespace App\Inputs;

use Illuminate\Validation\Rule;

class UserInputs extends BaseInputs
{
    public $username;
    public $password;
    public $age = 1;
    public $sex = 'man';

    public function rules()
    {
        return [
            'username' => "required|string",
            'password' => "required|string",
            'age' => "integer|digits_between:1,3",
            'sex' => Rule::in(['woman', 'man']),
        ];
    }
}
