<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;


class ResetPasswordController extends Controller
{

    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        //$user->update($request->only('password'));
        $user->password = Hash::make($request->password);
        $user->save();
        // delete current code
        //print_r($passwordReset);die();
        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
