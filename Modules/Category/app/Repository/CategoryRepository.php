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
        $image_url = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mimeType = $file->getMimeType();

            $image_name = time().'-'.$file->getClientOriginalName();
            $relative_path = 'images/categories/'.$image_name;

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

            imagejpeg($image, public_path($relative_path), 50);
            if ($mimeType === 'image/png') {
                imagepng($image, public_path($relative_path), 4);
            } elseif ($mimeType === 'image/gif') {
                imagegif($image, public_path($relative_path));
            }

            // آزاد کردن منابع تصویر
            imagedestroy($image);

            $image_url = $relative_path; // آدرس تصویر
        }

        Category::create([
            'name' => $request->name,
            'image' => $image_url, // استفاده از متغیر $image_url
            'parent_id' => $request->parent_id,
        ]);
    }

    public function update($category, $request)
    {
        try {
            $data = [
                'name' => $request->name ?: $category->name,
                'parent_id' => $request->parent_id,
            ];

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $mimeType = $file->getMimeType();
                $image_name = time().'-'.$file->getClientOriginalName();
                $relative_path = 'images/categories/'.$image_name;

                switch ($mimeType) {
                    case 'image/jpeg':
                    case 'image/pjpeg':
                        $image = imagecreatefromjpeg($file->getRealPath());
                        imagejpeg($image, public_path($relative_path), 50);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($file->getRealPath());
                        imagepng($image, public_path($relative_path), 4);
                        break;
                    case 'image/gif':
                        $image = imagecreatefromgif($file->getRealPath());
                        imagegif($image, public_path($relative_path));
                        break;
                }

                // آزاد کردن منابع تصویر
                imagedestroy($image);

                $data['image'] = $relative_path;
            } else {
                $data['image'] = $category->image;
            }

            $category->update($data);
        } catch (\Exception $e) {
            throw new \Exception('خطا در به‌روزرسانی دسته‌بندی: '.$e->getMessage());
        }
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
