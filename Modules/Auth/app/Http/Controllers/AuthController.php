<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Carbon;
use Modules\Auth\Http\Requests\loginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Jobs\RegisterJob as JobsRegisterJob;
use Modules\Auth\Repository\Authrepository;
use Modules\Otp\Http\Requests\RegisterVerificationOtpRequest;
use Modules\Otp\Http\Requests\ResendOtpRequest;
use Modules\Otp\Models\Otp;

class AuthController extends Controller
{
   private $authRepo;

   public function __construct(Authrepository $authRepo)
   {
        $this->authRepo = $authRepo; 
   }

   public function Register(RegisterRequest $request)
   {
    
    $user = $this->authRepo->register($request);
    if ($user) {
        JobsRegisterJob::dispatch($user);

        return response()->json(['message' => 'Registration successful, please check your email for verification code.'], 201);
    }

    return response()->json(['message' => __('messages.user.auth.register.failed')], 404);
   }

   public function verify_otp_code(Otp $otp, RegisterVerificationOtpRequest $request)
   {
       $code = $request->code;
       $otpRecord = Otp::where('otp', $code)->first();
   
       if (!$otpRecord) {
           return response()->json(['message' => 'User not found.'], 404);
       }
   
       if ($otpRecord->expire_time < Carbon::now()) {
           return response()->json(['message' => 'کد منقضی شده است. لطفاً کد جدید درخواست کنید.'], 410);
       }
   
       $user = User::find($otpRecord->user_id);
    //    dd($user);
   
       if (!$user) {
           return response()->json(['error' => 'User not found.'], 404);
       }

       if ($code === $otpRecord->otp) {
           $user->update(['email_verified_at' => Carbon::now()]);
           $otpRecord->delete();
           return response()->json(['message' => __('code.verified.successfully.')], 200);
       }
   
       return response()->json(['message' => 'کد نامعتبراست'], 404);
   }

    
    public function login(LoginRequest $request)
    {
        $accessToken = $this->authRepo->login($request);
        if ($accessToken) {
            return response()->json(['message' => __('messages.user.auth.login.success'), '__token__' => $accessToken], 200);
        }

        return response()->json(['message' => __('messages.user.auth.login.failed')], 403);
    }

    public function ResendCode(ResendOtpRequest $request){

        $error = $this->authRepo->ResendCode($request);

        if($error === null){
            return response()->json(['message' => 'messages.Code.Resend.successful'], 200);
        }
        return response()->json(['message' => __('messages.Code.Resend.failed')], 404);
    }




}
