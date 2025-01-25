<?php

namespace Modules\Profile\Repository;

use Illuminate\Support\Facades\Auth;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function update($user , $request)
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی کاربر';
        }

        $user->update([
            'username' => $request->username ? $request->username : $user->username,
            'lastname' => $request->lastname ? $request->lastname : $user->lastname,
            'mobile' => $request->mobile ? $request->mobile : $user->mobile,
            'email' => $request->email ? $request->email : $user->email,
            // 'avatar' => $request->avatar ? $request->avatar : $user->avatar,
        ]);
    }

    public function change_password ($request)
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی کاربر';
        }

        $user->update([
            'password' => password_hash($request->newpassword, PASSWORD_DEFAULT),
        ]);
    }
}