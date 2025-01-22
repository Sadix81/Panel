<?php

namespace Modules\Size\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Size\Http\Requests\CreateSizeRequest;
use Modules\Size\Http\Requests\UpdateSizeRequest;
use Modules\Size\Models\Size;
use Modules\Size\Repository\SizeRepository;
use Modules\Size\Transformers\IndexSizeResorce;
use Modules\Size\Transformers\ShowSizeResorce;

class SizeController extends Controller
{
    private $sizeRepo;

    public function __construct(SizeRepository $sizeRepo)
    {
        $this->sizeRepo = $sizeRepo;
    }

    public function index(){
        
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexSizeResorce::collection($this->sizeRepo->index());
    }

    public function store(CreateSizeRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->sizeRepo->store($request);
        if($error === null){
            return response()->json(['message' => __('messages.size.store.success', ['title' => $request->title])], 201);
        }

        return response()->json(['message' => __('messages.size.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Size $size)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowSizeResorce($size);

    }

    public function update(Size $size, UpdateSizeRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->sizeRepo->update($size, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.size.update.success', ['title' => $size->title])], 200);
        }

        return response()->json(['message' => __('messages.size.update.failed', ['title' => $size->title])], 500);
    }

    public function destroy($size)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->sizeRepo->delete($size);
        if ($error === null) {
            return response()->json(['message' => __('messages.size.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.size.delete.failed')], 500);
    }
}
