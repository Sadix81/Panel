<?php

namespace Modules\ForgotPassword\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\ForgotPassword\Http\Requests\ChangePasswordRequest;
use Modules\ForgotPassword\Http\Requests\ForgotPasswordRequest;
use Modules\ForgotPassword\Http\Requests\ForgotPasswordVerificationCodeRequest;
use Modules\ForgotPassword\Repository\ForgotPasswordRepository;
use Modules\Otp\Models\Otp;

class ForgotPasswordController extends Controller
{
    private $passRepo;

    public function __construct(ForgotPasswordRepository $forgotPassword)
    {
        $this->passRepo = $forgotPassword;
    }

    public function forgotpassword(ForgotPasswordRequest $request)
    {

        $error = $this->passRepo->password($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.otp.send.successfully')], 200);
        }

        return response()->json(['message' => __('messages.otp.send.failed')], 404);
    }

    public function verify_password_otp_code(Otp $otp, ForgotPasswordVerificationCodeRequest $request)
    {
        $code = $request->code;

        $otp = Otp::where('otp', $code)->first();

        if(! $otp){
            return response()->json(['error' => 'code not found.'], 404);
        }

        if($otp->expire_time < Carbon::now()){
            $otp->delete();
            return response()->json(['message' => 'کد منقضی شده است. لطفاً کد جدید درخواست کنید.'], 410); // 410 Gone
        }

        $user = User::find($otp->user_id);
    
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($request->code == $otp->otp) {
            return response()->json(['message' => __('code.verified.successfully.')], 200);
        }

        return response()->json(['message' => __('code.verified.failed.')], 404);
    }

    public function ChangePassword(User $user, ChangePasswordRequest $request)
    {
        if (! $user) {
            return response()->json(['message' => 'کاربر مورد نظر وجود ندارد'], 404);
        }

        if ($request->password !== $request->confirmpassword) {
            return response()->json(['message' => 'یکسان بودن دو مقدار الزامیست']);
        }

        $error = $this->passRepo->ChangePassword($user, $request);
        if ($error === null) {
            return response()->json(['message' => __('password.changed.successfully.')], 200);
        }

        return response()->json(['message' => __('password.changed.failed.')], 404);
    }
}
