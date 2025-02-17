<?php

namespace Modules\Product\Repository;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Property\Models\Property;

class ProductRepository implements ProductRepositoryInterface
{
    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'category_id' => request()->has('category_id') ? request('category_id') : null,
            'search' => request()->has('search') ? request('search') : null,
            'price' => request()->has('price') ? request('price') : null,
            'status' => request()->has('status') ? 1 : null,
            'is_sale' => request()->has('is_sale') ? 1 : null,
        ];

        try {
            $query = Product::query();

            if (! empty($req['status'])) {
                $query->where('status', 1);
            }

            if (! empty($req['search'])) {
                $query->where('name', 'like', '%'.$req['search'].'%');
            }

            // Filter by properties
            $query->whereHas('properties', function ($query) use ($req) {
                if (! empty($req['price'])) {
                    $query->where('price', '>=', $req['price']);
                }
                if (! empty($req['category_id'])) {
                    $query->where('category_id', $req['category_id']);
                }
                if (! empty($req['is_sale'])) {
                    $query->where('discounted_price', '>' , 0);
                }
            });

            $products = $query->orderBy($req['sort'], $req['order'])
                ->paginate($req['limit']);

            return $products;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'thumbnail' => $request->thumbnail,
            ]);

            if ($request->has('category_id')) {
                $product->categories()->attach($request->category_id);
            }

            if ($request->hasFile('image_url')) {
                foreach ($request->file('image_url') as $file) {
                    // بررسی اینکه آیا فایل معتبر است
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $mimeType = $file->getMimeType();
                        $image_name = time().'-'.$file->getClientOriginalName();
                        $image_size = $file->getSize(); // دریافت سایز تصویر
            
                        // بارگذاری تصویر با توجه به نوع MIME
                        switch ($mimeType) {
                            case 'image/jpeg':
                            case 'image/pjpeg':
                                $image = imagecreatefromjpeg($file->getRealPath());
                                imagejpeg($image, public_path('images/'.$image_name), 50);
                                break;
                            case 'image/png':
                                $image = imagecreatefrompng($file->getRealPath());
                                imagepng($image, public_path('images/'.$image_name), 4);
                                break;
                            case 'image/gif':
                                $image = imagecreatefromgif($file->getRealPath());
                                imagegif($image, public_path('images/'.$image_name));
                                break;
                            default:
                                return response()->json(['message' => 'فرمت فایل پشتیبانی نمی‌شود.'], 400);
                        }
            
                        // آزاد کردن منابع تصویر
                        imagedestroy($image);
            
                        // ذخیره آدرس تصویر و اطلاعات اضافی در جدول تصاویر
                        $product->images()->create([
                            'image_url' => asset('images/'.$image_name),
                            'image_type' => $mimeType, // ذخیره نوع تصویر
                            'image_size' => $image_size, // ذخیره سایز تصویر
                        ]);
                    }
                }
            }

            $categoryIds = $request->category_id; // Array of category IDs
            $colorIds = $request->color_id; // Array of color IDs
            $sizeIds = $request->size_id; // Array of size IDs
            $combinations = [];

            foreach ($categoryIds as $categoryId) {
                if (is_array($colorIds) && is_array($sizeIds)) {
                    // Both color and size are provided
                    foreach ($colorIds as $colorId) {
                        foreach ($sizeIds as $sizeId) {
                            $combinations[] = [
                                'category_id' => $categoryId,
                                'color_id' => $colorId,
                                'size_id' => $sizeId,
                            ];
                        }
                    }
                } elseif (is_array($colorIds)) {
                    // Only color is provided
                    foreach ($colorIds as $colorId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => $colorId,
                            'size_id' => null, // Size is null
                        ];
                    }
                } elseif (is_array($sizeIds)) {
                    // Only size is provided
                    foreach ($sizeIds as $sizeId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // Color is null
                            'size_id' => $sizeId,
                        ];
                    }
                } else {
                    // Neither color nor size is provided
                    $combinations[] = [
                        'category_id' => $categoryId,
                        'color_id' => null,
                        'size_id' => null,
                    ];
                }
            }

            if($request->type && $request->amount && $request->type == 'fixed'){
                $final_price = $request->price - $request->amount;
            }

            if($request->type && $request->amount && $request->type == 'percentage'){
                $price = ($request->price * $request->amount) / 100;
                $final_price = $request->price - $price;
            }
            // Insert combinations into the database
            foreach ($combinations as $combination) {
                Property::create([
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'category_id' => $combination['category_id'],
                    'color_id' => $combination['color_id'],
                    'size_id' => $combination['size_id'],
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'discounted_price' => $request->type ? $final_price : null,
                    'product_id' => $request->product_id ?: $product->id,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($product, $request)
    {
        DB::beginTransaction();

        try {
            $product->update([
                'name' => $request->name ? $request->name : $product->name,
                'description' => $request->description ? $request->description : $product->description,
                'status' => $request->status ? $request->status : $product->status,
                'thumbnail' => $request->thumbnail ? $request->thumbnail : $product->thumbnail,
            ]);
            if ($request->has('category_id')) {
                if (is_array($request->category_id) && ! empty($request->category_id)) {
                    $validCategoryIds = array_filter($request->category_id, function ($id) { // filter invalid data
                        return is_numeric($id) && $id > 0;  // return valid data
                    });

                    if (! empty($validCategoryIds)) {
                        $product->categories()->sync($validCategoryIds);
                    }
                }
            }

            $categoryIds = $request->category_id; // آرایه‌ای از IDهای دسته‌بندی
            $colorIds = $request->color_id; // آرایه‌ای از IDهای رنگ، می‌تواند null باشد
            $sizeIds = $request->size_id; // آرایه‌ای از IDهای سایز، می‌تواند null باشد

            if (! is_array($categoryIds)) {
                return response()->json(['message' => 'Invalid input. category_id must be an array.'], 400);
            }

            Property::where('product_id', $product->id)->delete();

            $combinations = [];
            foreach ($categoryIds as $categoryId) {
                if (is_array($colorIds) && is_array($sizeIds)) {
                    // هر دو رنگ و سایز موجود است
                    foreach ($colorIds as $colorId) {
                        foreach ($sizeIds as $sizeId) {
                            $combinations[] = [
                                'category_id' => $categoryId,
                                'color_id' => $colorId,
                                'size_id' => $sizeId,
                            ];
                        }
                    }
                } elseif (is_array($colorIds)) {
                    // فقط رنگ موجود است
                    foreach ($colorIds as $colorId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => $colorId,
                            'size_id' => null, // سایز null است
                        ];
                    }
                } elseif (is_array($sizeIds)) {
                    // فقط سایز موجود است
                    foreach ($sizeIds as $sizeId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // رنگ null است
                            'size_id' => $sizeId,
                        ];
                    }
                } else {
                    // هیچ‌کدام موجود نیستند
                    $combinations[] = [
                        'category_id' => $categoryId,
                        'color_id' => null,
                        'size_id' => null,
                    ];
                }
            }

            if($request->type && $request->amount && $request->type == 'fixed'){
                $final_price = $request->price - $request->amount;
            }

            if($request->type && $request->amount && $request->type == 'percentage'){
                $price = ($request->price * $request->amount) / 100;
                $final_price = $request->price - $price;
            }

            foreach ($combinations as $combination) {
                Property::create([
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'category_id' => $combination['category_id'],
                    'color_id' => $combination['color_id'],
                    'size_id' => $combination['size_id'],
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'discounted_price' => $request->type ? $final_price : null,
                    'product_id' => $product->id,
                ]);
            }

            if ($request->hasFile('image_url')) {
                // اگر محصول قبلاً عکسی داشت، آن را حذف کنید
                if ($product->images()->count() > 0) {
                    foreach ($product->images as $image) {
                        // حذف فایل تصویر از سرور
                        $imagePath = public_path('images/'.basename($image->image_url)); // اطمینان از اینکه فقط نام فایل گرفته می‌شود
                        if (file_exists($imagePath) && is_file($imagePath)) { // بررسی اینکه آیا واقعاً یک فایل است
                            unlink($imagePath);
                        }
                        $image->delete();
                    }
                }

                // ذخیره تصاویر جدید
                foreach ($request->file('image_url') as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $mimeType = $file->getMimeType();
                        $image_name = time().'-'.$file->getClientOriginalName();

                        switch ($mimeType) {
                            case 'image/jpeg':
                            case 'image/pjpeg':
                                $image = imagecreatefromjpeg($file->getRealPath());
                                imagejpeg($image, public_path('images/'.$image_name), 50);
                                break;
                            case 'image/png':
                                $image = imagecreatefrompng($file->getRealPath());
                                imagepng($image, public_path('images/'.$image_name), 4);
                                break;
                            case 'image/gif':
                                $image = imagecreatefromgif($file->getRealPath());
                                imagegif($image, public_path('images/'.$image_name));
                                break;
                            default:
                                return response()->json(['message' => 'فرمت فایل پشتیبانی نمی‌شود.'], 400);
                        }

                        imagedestroy($image);

                        // ذخیره آدرس تصویر در جدول تصاویر
                        $product->images()->create([
                            'image_url' => asset('images/'.$image_name), // استفاده از 'image_url'
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function thumbnail($product)
    {

        $product->update([
            'thumbnail' => null,
        ]);
    }

    public function product_iamge($product)
    {
        $product_images = Product::whereHas('images', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })
            ->with('images')->first(); // اطمینان از اینکه حتما تصاویر را بگیرد
        // بررسی وجود تصاویر
        if ($product_images && $product_images->images) {
            foreach ($product_images->images as $image) {
                // حذف فایل تصویر از سرور
                if (file_exists(public_path('images/'.basename($image->image_url)))) {
                    unlink(public_path('images/'.basename($image->image_url))); // حذف فایل تصویر
                }
                $image->delete();
            }
        }
    }

    public function delete($product)
    {
        $product->delete();
    }
}
