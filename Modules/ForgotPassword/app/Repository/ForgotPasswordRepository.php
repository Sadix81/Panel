<?php

namespace Modules\ForgotPassword\Repository;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\ForgotPassword\Emails\ForgotPasswordMail;
use Modules\Otp\Models\Otp;

class ForgotPasswordRepository implements ForgotPasswordRepositorynterface
{
    public function password($request)
    {
        $user_email = $request->email;

        $user = User::where('email', $user_email)->first();

        if (! $user) {
            return response()->json(['message' => 'کاربر مورد نظر وجود ندارد']);
        }

        $otp = rand(11111, 99999);
        $user = User::where('email', $user->email)->first();
        $user->otps()->create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expire_time' => Carbon::now()->addSeconds(300),
        ]);

        $otps = $user->otps()->select('otp', 'user_id')->latest()->first(); // test for moer than 1 user

        Log::info("The Forgot Password Code for {$user->username} (ID: {$user->id}): is  $otps->otp");
        Mail::to($user->email)->send(new ForgotPasswordMail($user->username, $otp));

    }

    public function ChangePassword($user, $request)
    {
        $user = User::where('email', $request->email)->first();
        $user_otp = Otp::where('user_id', $user->id)->first();

        if (! $user_otp) {
            return response()->json(['message' => 'کاربر مورد نظر وجود ندارد'], 404);
        }

        try {
            $user->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
            ]);
            $user_otp->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
