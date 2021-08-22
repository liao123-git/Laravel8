<?php

namespace App\Http\Controllers\WX;

use App\CodeResponse;
use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Inputs\UserInputs;
use App\Models\User;
use App\Servers\UserServers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends WXController
{
    protected $only = ['user'];

    public function user()
    {
        $users = User::all();
        foreach ($users as $item){
            $first_name = $item->first_name;
        }

        /** @var User $user */
        $user = Auth::guard('wx')->user();
        $first_name = $user->first_name;

        return $this->codeReturn($user);
    }

    //
    public function test()
    {
        // $user = (new UserServers())->getUser(); 频繁的创建与销毁

        $user = UserServers::getInstance()->getUser();
        return $this->codeReturn(['name' => $user, 'number' => UserServers::getInstance()->getUser()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BaseException
     */
    public function login(Request $request)
    {
        $input = UserInputs::new($request->input());

        $user = User::where("username", $input->username)->first();
        if (!$user) $this->fail(CodeResponse::LOGIN_FAIL, "user invalid");
        if ($user->password !== $input->password) $this->fail(CodeResponse::LOGIN_FAIL, "password was wrong");

        $token = Auth::guard('wx')->login($user);
        $user->update(['token' => $token]);
        $user->age = $input->age;
        $user->sex = $input->sex;

        return $this->codeReturn([
            "userInfo" => $user,
        ]);
    }
}
