<?php

namespace Modules\Auth\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\RegisterMail;
use Carbon\Carbon;

class RegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $otp = rand(11111, 99999);
        $this->user->otps()->create([
            'user_id' => $this->user->id,
            'otp' => $otp,
            'expire_time' => Carbon::now()->addMinutes(120),
        ]);
        Log::info("The Email validation Code for {$this->user->username} (ID: {$this->user->id}): is $otp");
        Mail::to($this->user->email)->send(new RegisterMail($this->user->username, $otp));
    }
}
