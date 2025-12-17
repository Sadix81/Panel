<?php

namespace Modules\Product\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'code_num' => request()->has('code_num') ? request('code_num') : null,
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

            if (! empty($req['code_num'])) {
                $query->where('code', $req['code_num']); // Ensures exact match
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
                    $query->where('discounted_price', '>', 0);
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
                'code' => $request->code,
                'description' => $request->description,
                'status' => $request->status,
                'thumbnail' => null,
            ]);

            if ($request->has('category_id')) {
                $product->categories()->attach($request->category_id);
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailFile = $request->file('thumbnail');
                $thumbnailMimeType = $thumbnailFile->getMimeType();
                $thumbnailName = time().'-'.$thumbnailFile->getClientOriginalName();
                $thumbnailPath = public_path('images/products/thumbnail/'.$thumbnailName);

                if (in_array($thumbnailMimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                    switch ($thumbnailMimeType) {
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $image = imagecreatefromjpeg($thumbnailFile->getRealPath());
                            imagejpeg($image, $thumbnailPath, 50);
                            break;
                        case 'image/png':
                            $image = imagecreatefrompng($thumbnailFile->getRealPath());
                            imagepng($image, $thumbnailPath, 4);
                            break;
                        case 'image/gif':
                            $image = imagecreatefromgif($thumbnailFile->getRealPath());
                            imagegif($image, $thumbnailPath);
                            break;
                    }

                    // آزاد کردن منابع تصویر
                    imagedestroy($image);

                    $product->thumbnail = asset('images/products/thumbnail/'.$thumbnailName);
                    $product->save();
                } else {
                    return response()->json(['message' => 'فرمت فایل thumbnail پشتیبانی نمی‌شود.'], 400);
                }
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
                                imagejpeg($image, public_path('images/products/'.$image_name), 50);
                                break;
                            case 'image/png':
                                $image = imagecreatefrompng($file->getRealPath());
                                imagepng($image, public_path('images/products/'.$image_name), 4);
                                break;
                            case 'image/gif':
                                $image = imagecreatefromgif($file->getRealPath());
                                imagegif($image, public_path('images/products/'.$image_name));
                                break;
                            default:
                                return response()->json(['message' => 'فرمت فایل پشتیبانی نمی‌شود.'], 400);
                        }
                        // آزاد کردن منابع تصویر
                        imagedestroy($image);

                        try {
                            // ذخیره آدرس تصویر و اطلاعات اضافی در جدول تصاویر
                            $product->images()->create([
                                'image_url' => asset('images/products/'.$image_name),
                                'image_type' => $mimeType, // ذخیره نوع تصویر
                                'image_size' => $image_size, // ذخیره سایز تصویر
                            ]);
                        } catch (\Throwable $th) {
                            Log::error('Error saving image: '.$th->getMessage());
                        }
                    }
                }
            }

            $categoryIds = $request->category_id;
            $colorIds = $request->color_id;
            $sizeIds = $request->size_id;
            $materialIds = $request->material_id;
            $weightIds = $request->weight_id;
            $combinations = [];

            foreach ($categoryIds as $categoryId) {
                if (is_array($colorIds) && is_array($sizeIds) && is_array($materialIds) && is_array($weightIds)) {
                    foreach ($colorIds as $colorId) {
                        foreach ($sizeIds as $sizeId) {
                            foreach ($materialIds as $materialId) {
                                foreach ($weightIds as $weightId) {
                                    $combinations[] = [
                                        'category_id' => $categoryId,
                                        'color_id' => $colorId,
                                        'size_id' => $sizeId,
                                        'material_id' => $materialId,
                                        'weight_id' => $weightId,
                                    ];
                                }
                            }
                        }
                    }
                } elseif (is_array($colorIds)) {
                    // Only color is provided
                    foreach ($colorIds as $colorId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => $colorId,
                            'size_id' => null, // Size is null
                            'material_id' => null,
                            'weight_id' => null,
                        ];
                    }
                } elseif (is_array($sizeIds)) {
                    // Only size is provided
                    foreach ($sizeIds as $sizeId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // Color is null
                            'size_id' => $sizeId,
                            'material_id' => null,
                            'weight_id' => null,
                        ];
                    }
                } elseif (is_array($materialIds)) {
                    // Only material is provided
                    foreach ($materialIds as $materialId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // Color is null
                            'size_id' => null,
                            'material_id' => $materialId,
                            'weight_id' => null,
                        ];
                    }
                } elseif (is_array($weightIds)) {
                    // Only weight is provided
                    foreach ($weightIds as $weightId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // Color is null
                            'size_id' => null,
                            'material_id' => null,
                            'weight_id' => $weightId,
                        ];
                    }
                } else {
                    // Nothing except category
                    $combinations[] = [
                        'category_id' => $categoryId,
                        'color_id' => null,
                        'size_id' => null,
                        'material_id' => null,
                        'weight_id' => null,
                    ];
                }
            }

            if ($request->type && $request->amount && $request->type == 'fixed') {
                $final_price = $request->price - $request->amount;
            }

            if ($request->type && $request->amount && $request->type == 'percentage') {
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
                    'material_id' => $combination['material_id'],
                    'weight_id' => $combination['weight_id'],
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
                'code' => $request->code ? $request->code : $product->code,
                'code' => $request->code ? $request->code : $product->code,
                'description' => $request->description ? $request->description : $product->description,
                'status' => $request->status ? $request->status : $product->status,
            ]);

            if ($request->has('category_id')) {
                if (is_array($request->category_id) && ! empty($request->category_id)) {
                    $validCategoryIds = array_filter($request->category_id, function ($id) {
                        return is_numeric($id) && $id > 0; // فیلتر کردن داده‌های نامعتبر
                    });

                    if (! empty($validCategoryIds)) {
                        $product->categories()->sync($validCategoryIds);
                    }
                }
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailFile = $request->file('thumbnail');
                $thumbnailMimeType = $thumbnailFile->getMimeType();
                $thumbnailName = time().'-'.$thumbnailFile->getClientOriginalName();
                $thumbnailPath = public_path('images/products/thumbnail/'.$thumbnailName);

                if (in_array($thumbnailMimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                    switch ($thumbnailMimeType) {
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $image = imagecreatefromjpeg($thumbnailFile->getRealPath());
                            imagejpeg($image, $thumbnailPath, 50);
                            break;
                        case 'image/png':
                            $image = imagecreatefrompng($thumbnailFile->getRealPath());
                            imagepng($image, $thumbnailPath, 4);
                            break;
                        case 'image/gif':
                            $image = imagecreatefromgif($thumbnailFile->getRealPath());
                            imagegif($image, $thumbnailPath);
                            break;
                    }

                    imagedestroy($image);

                    if ($product->thumbnail) {
                        $oldThumbnailPath = public_path('images/products/thumbnail/'.basename($product->thumbnail));
                        if (file_exists($oldThumbnailPath)) {
                            unlink($oldThumbnailPath); // حذف تصویر قدیمی
                        }
                    }

                    $product->thumbnail = asset('images/products/thumbnail/'.$thumbnailName);
                } else {
                    return response()->json(['message' => 'فرمت فایل thumbnail پشتیبانی نمی‌شود.'], 400);
                }
            }

            $product->save();

            Property::where('product_id', $product->id)->delete();

            $categoryIds = $request->category_id;
            $colorIds = $request->color_id;
            $sizeIds = $request->size_id;
            $materialIds = $request->material_id;
            $weightIds = $request->weight_id;

            if (! is_array($categoryIds)) {
                return response()->json(['message' => 'Invalid input. category_id must be an array.'], 400);
            }
            Property::where('product_id', $product->id)->delete();

            $combinations = [];
            foreach ($categoryIds as $categoryId) {
                if (is_array($colorIds) && is_array($sizeIds) && is_array($materialIds) && is_array($weightIds)) {
                    foreach ($colorIds as $colorId) {
                        foreach ($sizeIds as $sizeId) {
                            foreach ($materialIds as $materialId) {
                                foreach ($weightIds as $weightId) {
                                    $combinations[] = [
                                        'category_id' => $categoryId,
                                        'color_id' => $colorId,
                                        'size_id' => $sizeId,
                                        'material_id' => $materialId,
                                        'weight_id' => $weightId,
                                    ];
                                }
                            }
                        }
                    }
                } elseif (is_array($colorIds)) {
                    foreach ($colorIds as $colorId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => $colorId,
                            'size_id' => null,
                            'material_id' => null,
                            'weight_id' => null,
                        ];
                    }
                } elseif (is_array($sizeIds)) {
                    foreach ($sizeIds as $sizeId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null,
                            'size_id' => $sizeId,
                            'material_id' => null,
                            'weight_id' => null,
                        ];
                    }
                } elseif (is_array($materialIds)) {
                    foreach ($materialIds as $materialId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null,
                            'size_id' => null,
                            'material_id' => $materialId,
                            'weight_id' => null,
                        ];
                    }
                } elseif (is_array($weightIds)) {
                    foreach ($weightIds as $weightId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null,
                            'size_id' => null,
                            'material_id' => null,
                            'weight_id' => $weightId,
                        ];
                    }
                } else {
                    $combinations[] = [
                        'category_id' => $categoryId,
                        'color_id' => null,
                        'size_id' => null,
                        'material_id' => null,
                        'weight_id' => null,
                    ];
                }
            }

            if ($request->type && $request->amount && $request->type == 'fixed') {
                $final_price = $request->price - $request->amount;
            }

            if ($request->type && $request->amount && $request->type == 'percentage') {
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
                    'material_id' => $combination['material_id'],
                    'weight_id' => $combination['weight_id'],
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'discounted_price' => $request->type ? $final_price : null,
                    'product_id' => $product->id,
                ]);
            }

            if ($request->hasFile('image_url')) {
                if ($product->images()->count() > 0) {
                    foreach ($product->images as $image) {
                        $imagePath = public_path('images/products/'.basename($image->image_url));
                        if (file_exists($imagePath) && is_file($imagePath)) {
                            unlink($imagePath);
                        }
                        $image->delete();
                    }
                }

                // ذخیره تصاویر جدید
                foreach ($request->file('image_url') as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $mimeType = $file->getMimeType();
                        $image_size = $file->getSize();
                        $image_name = time().'-'.$file->getClientOriginalName();

                        switch ($mimeType) {
                            case 'image/jpeg':
                            case 'image/pjpeg':
                                $image = imagecreatefromjpeg($file->getRealPath());
                                imagejpeg($image, public_path('images/products/'.$image_name), 50);
                                break;
                            case 'image/png':
                                $image = imagecreatefrompng($file->getRealPath());
                                imagepng($image, public_path('images/products/'.$image_name), 4);
                                break;
                            case 'image/gif':
                                $image = imagecreatefromgif($file->getRealPath());
                                imagegif($image, public_path('images/products/'.$image_name));
                                break;
                            default:
                                return response()->json(['message' => 'فرمت فایل پشتیبانی نمی‌شود.'], 400);
                        }

                        imagedestroy($image);
                        try {
                            $product->images()->create([
                                'image_url' => asset('images/products/'.$image_name),
                                'image_type' => $mimeType,
                                'image_size' => $image_size,
                            ]);
                        } catch (\Throwable $th) {
                            Log::error('Error saving image: '.$th->getMessage());
                        }
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

    public function product_image($product)
    {
        $product_images = Product::whereHas('images', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })
            ->with('images')->first();
        if ($product_images && $product_images->images) {
            foreach ($product_images->images as $image) {
                if (file_exists(public_path('images/'.basename($image->image_url)))) {
                    unlink(public_path('images/'.basename($image->image_url)));
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
