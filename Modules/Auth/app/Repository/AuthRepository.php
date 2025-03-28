<?php

namespace Modules\Auth\Repository;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\RegisterMail;
use Modules\Otp\Models\Otp;

class AuthRepository implements AuthRepositoryInterface
{
    public function register($request)
    {
        $allUsers = User::all(['username', 'mobile', 'email'])->toArray();

        $usernames = array_column($allUsers, 'username');
        $mobiles = array_column($allUsers, 'mobile');
        $emails = array_column($allUsers, 'email');

        if (in_array($request->username, $usernames)) {
            return response()->json(['messages' => 'username has already selected'], 409); // 409 for conflict
        }

        if (in_array($request->mobile, $mobiles)) {
            return response()->json(['messages' => 'mobile has already selected'], 409); // 409 for conflict
        }

        if (in_array($request->username, $emails)) {
            return response()->json(['messages' => 'email has already selected'], 409);  // 409 for conflict
        }

        $user = User::create([
            'username' => $request->username,
            'lastname' => $request->lastname,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'avatar' => $request->avatar,
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
        ]);

        return $user;
    }

    public function login($request)
    {
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'کاربر یافت نشد'], 404);
        }

        if ($user && ! password_verify($request->password, $user->password)) {
            return response()->json(['message' => 'کاربر یافت نشد'], 404);
        }

        if ($user && password_verify($request->password, $user->password)) {
            $token = $user->createToken('__Token__')->accessToken;

            return [
                // 'token_name' => '__Token__', // Specify the name of the token
                '__token__' => $token, // The actual token
            ];
        }

        return null;
    }

    public function TwoFactorLoginEamil($request)
    {
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->email)->first();

        if (! $user) {
            return response()->json('.کاربر یافت نشد');
        }

        return $user;
    }

    public function TwoFactorLogin($request)
    {
        $code = $request->code;
        $otpRecord = Otp::where('otp', $code)->first();
        $user = User::find($otpRecord->user_id);

        if (! $user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $token = $user->createToken('__Token__')->accessToken;

        $otpRecord->delete();

        return [
            // 'token_name' => '__Token__', // Specify the name of the token
            '__token__' => $token, // The actual token
        ];

        return null;
    }

    public function ResendCode($request)
    {
        DB::beginTransaction();

        try {
            $user = User::where('email', $request->email)->first();
            $last_user_otp = Otp::where('user_id', $user->id)->first();

            if (! $user) {
                return response()->json(['meesage => کاربر یافت نشد']);
            }

            if ($last_user_otp) {
                $last_user_otp->delete();
            }

            $otp = rand(11111, 99999);
            $user->otps()->create([
                'user_id' => $user->id,
                'otp' => $otp,
                'expire_time' => Carbon::now()->addMinutes(120),
            ]);

            Log::info('Email validation Code for '.$user->id.': '.$otp);
            Mail::to($user->email)->send(new RegisterMail($user->username, $otp));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->token()->revoke();
        }
    }
}
