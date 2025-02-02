<?php

namespace Modules\Category\Repository;

use Modules\Category\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $category = Category::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('name', 'like', '%'.$req['search'].'%');
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $category;

    }

    public function store($request)
    {
        if (request()->hasFile('image')) {
            $file = $request->file('image');
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
        }

        $category = Category::create([
            'name' => $request->name,
            'image' => $request->image ? $image_url : null,
            'parent_id' => $request->parent_id,
        ]);
        $category->save();
    }

    public function update($category, $request)
    {
        if (request()->hasFile('image')) {
            $file = $request->file('image');
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
            $image_url = $category->avatar;
        }

        $category->update([
            'name' => $request->name ? $request->name : $category->name,
            'image' => $request->image ? $image_url : $category->image,
            'parent_id' => $request->parent_id,
        ]);
    }

    public function remove_category_image($category)
    {
        $category->update([
            'image' => null,
        ]);
    }

    public function delete($category)
    {
        $category = Category::find($category);

        if (! $category) {
            return response()->json(['message' => __('messages.category.not_found')], 404);
        }

        $all_categories_parent_id = Category::pluck('parent_id')->toArray(); // get all parent_id(s)

        if (in_array($category->id, $all_categories_parent_id)) {
            Category::where('parent_id', $category->id)->update(['parent_id' => null]);
        }
        $category->delete();
    }
}
