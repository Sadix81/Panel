<?php

namespace Modules\Profile\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function update($user, $request)
    {
        $oldImageUrl = $user->avatar;
        Log::info('User ID ' . $user->id . ' is updating profile.'); // ثبت لاگ شروع به‌روزرسانی

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $mimeType = $file->getMimeType();
            $image_name = time() . '-' . $file->getClientOriginalName();

            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $image = imagecreatefromjpeg($file->getRealPath());
                    imagejpeg($image, public_path('images/profile/' . $image_name), 50);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file->getRealPath());
                    imagepng($image, public_path('images/profile/' . $image_name), 4);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($file->getRealPath());
                    imagegif($image, public_path('images/profile/' . $image_name));
                    break;
                default:
                    Log::warning('User ID ' . $user->id . ' uploaded an unsupported file type: ' . $mimeType);
                    return response()->json(['message' => 'فرمت فایل پشتیبانی نمی‌شود.'], 400);
            }

            imagedestroy($image);
            $image_url = asset('images/profile/' . $image_name);

            if ($oldImageUrl) {
                $oldImagePath = public_path('images/profile/' . basename($oldImageUrl));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                    Log::info('Deleted old avatar for user ID ' . $user->id);

                }
            }
        } else {
            $image_url = $oldImageUrl;
        }

        try {
            $user->update([
                'username' => $request->username ?: $user->username,
                'lastname' => $request->lastname ?: $user->lastname,
                'mobile' => $request->mobile ?: $user->mobile,
                'email' => $request->email ?: $user->email,
                'avatar' => $image_url,
                'country' => $request->country ?: $user->country,
                'province' => $request->province ?: $user->province,
                'city' => $request->city ?: $user->city,
                'address' => $request->address ?: $user->address,
                'codepost' => $request->codepost ?: $user->codepost,
                'twofactor' => $request->twofactor !== null ? $request->twofactor : $user->twofactor,
            ]);
                Log::info('User ID ' . $user->id . ' profile updated successfully.');
                return null;
            } catch (\Exception $e) {
                Log::error('Profile update failed for user ID ' . $user->id . ': ' . $e->getMessage());
                return response()->json(['message' => __('messages.user.profile.update.failed'), 'error' => $e->getMessage()], 500);
            }

            return response()->json(['message' => __('messages.user.profile.update.success')], 200);

    }

    public function delete_avatar($user)
    {
        $user = Auth::user();

        if (!$user) {
            Log::warning('User deletion attempt without authentication.');
            return response()->json(['message' => 'عدم دسترسی کاربر'], 403);
        }

        if ($user->avatar) {
            $avatarPath = public_path('images/profile/' . basename($user->avatar)); // مسیر فایل آواتار

            if (file_exists($avatarPath)) {
                unlink($avatarPath);
                Log::info('Deleted avatar for user ID ' . $user->id); // ثبت لاگ حذف تصویر آواتار
            }
        }

        $user->update([
            'avatar' => null,
        ]);
    }

    public function change_password($request)
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
