<?php

namespace Modules\Auth\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\RegisterMail;
use Carbon\Carbon;
use Modules\Auth\Http\Requests\ResendOtpRequest;
use Modules\Otp\Models\Otp;

class Authrepository implements AuthrepositoryInterface{

    public function register($request)
    {
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
        $user = User::where('username' , $request->username)
        ->orWhere('email' , $request->email)->first();

        if (! $user) {
            return response()->json('.کاربر یافت نشد');
        }

        if($user && password_verify($request->password , $user->password)){
            $token = $user->createToken('__Token__')->accessToken;

            return $token;
        }
        return null;
    }

    public function ResendCode($request){
        try {
            $user = User::where('email' , $request->email)->first();
            $last_user_otp = Otp::where('user_id' , $user->id)->first();
    
            if(!$user){
                return response()->json(['meesage => کاربر یافت نشد']);
            }

            if($last_user_otp){
                $last_user_otp->delete();
            }
    
            $otp = rand(11111, 99999);
            $user->otps()->create([
                'user_id' =>  $user->id,
                'otp' => $otp,
                'expire_time' => Carbon::now()->addMinutes(120),
            ]);
    
            Log::info('Email validation Code for '. $user->id.': '.$otp);
            Mail::to( $user->email)->send(new RegisterMail( $user->username, $otp));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout($request)
    {
        $user = Auth::user();
        $user->token()->revoke();
    }
}