<?php

namespace Modules\Profile\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Profile\Http\Requests\UpdatePasswordRequest;
use Modules\Profile\Http\Requests\UpdateProfileRequest;
use Modules\Profile\Repository\ProfileRepository;

class ProfileController extends Controller
{
    private $profileRepo;

    public function __construct(ProfileRepository $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

    public function update(User $user, UpdateProfileRequest $request)
    {

        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->profileRepo->update($user, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.profile.update.success')], 200);
        }

        return response()->json(['message' => __('messages.user.profile.update.failed')], 500);
    }

    public function delete_avatar(User $user)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }
        $error = $this->profileRepo->delete_avatar($user);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.profile.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.user.profile.delete.failed')], 500);
    }

    public function change_password(UpdatePasswordRequest $request)
    {

        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        // check if our current pass is correct or not
        if (! password_verify($request->currentpassword, $user->password)) {
            return response()->json(['message' => 'رمز وارد شده نادرست است']);
        }

        // check if the new password isnt similar with current one
        if ($request->newpassword === $request->currentpassword) {
            return response()->json(['message' => 'رمز جدید باید متفاوت از پسورد فعلی باشد']);
        }

        $error = $this->profileRepo->change_password($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.password.update.success')], 200);
        }

        return response()->json(['message' => __('messages.user.password.update.failed')], 500);
    }
}
