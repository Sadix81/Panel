<?php

namespace Modules\Profile\Repository;

use Illuminate\Support\Facades\Auth;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function update($user, $request)
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی کاربر';
        }

        if (request()->hasFile('avatar')) {
            $file = $request->file('avatar');
            $mimeType = $file->getMimeType();

            // ایجاد نام یونیک برای تصویر
            $image_name = time().'-'.$file->getClientOriginalName();

            // بارگذاری تصویر با توجه به نوع MIME
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    $image = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file->getRealPath());
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($file->getRealPath());
                    break;
                default:
                    return response()->json(['message' => 'فرمت فایل پشتیبانی نمی‌شود.'], 400);
            }

            // JPEG
            if ($mimeType === 'image/jpeg' || $mimeType === 'image/pjpeg') {
                imagejpeg($image, public_path('images/'.$image_name), 50); // 30 درصد کیفیت
            } else {
                // PNG(0 / 9) هرچی عدد بیشتر شود فشرده ساری بیشتر میشود
                if ($mimeType === 'image/png') {
                    imagepng($image, public_path('images/'.$image_name), 4);
                } elseif ($mimeType === 'image/gif') {
                    imagegif($image, public_path('images/'.$image_name));
                }
            }

            // آزاد کردن منابع تصویر
            imagedestroy($image);

            $image_url = asset('images/'.$image_name);
        } else {
            // اگر تصویری آپلود نشده، URL قبلی را حفظ کنید
            $image_url = $user->avatar;
        }

        $user->update([
            'username' => $request->username ? $request->username : $user->username,
            'lastname' => $request->lastname ? $request->lastname : $user->lastname,
            'mobile' => $request->mobile ? $request->mobile : $user->mobile,
            'email' => $request->email ? $request->email : $user->email,
            'avatar' => $request->avatar ? $image_url : $user->avatar,
        ]);
    }

    public function delete_avatar($user)
    {
        $user = Auth::user();

        if (! $user) {
            return 'عدم دسترسی کاربر';
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
