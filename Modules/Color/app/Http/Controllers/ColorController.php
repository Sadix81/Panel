<?php

namespace Modules\Color\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Color\Http\Requests\CreateColorRequest;
use Modules\Color\Http\Requests\UpdateColorRequest;
use Modules\Color\Models\Color;
use Modules\Color\Repository\ColorRepository;
use Modules\Color\Transformers\IndexColorRequest;
use Modules\Color\Transformers\ShowColorRequest;

class ColorController extends Controller
{
    private $colorRepo;

    public function __construct(ColorRepository $colorRepo)
    {
        $this->colorRepo = $colorRepo;
    }

    public function index()
    {

        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexColorRequest::collection($this->colorRepo->index());
    }

    public function store(CreateColorRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->colorRepo->store($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.color.store.success', ['name' => $request->name])], 201);
        }

        return response()->json(['message' => __('messages.color.store.failed', ['name' => $request->name])], 500);
    }

    public function show(Color $color)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowColorRequest($color);

    }

    public function update(Color $color, UpdateColorRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->colorRepo->update($color, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.color.update.success', ['name' => $color->name])], 200);
        }

        return response()->json(['message' => __('messages.color.update.failed', ['name' => $color->name])], 500);
    }

    public function destroy($color)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->colorRepo->delete($color);
        if ($error === null) {
            return response()->json(['message' => __('messages.color.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.color.delete.failed')], 500);
    }
}
