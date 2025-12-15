<?php

namespace Modules\Weight\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Weight\Http\Requests\CreateWeightRequest;
use Modules\Weight\Http\Requests\UpdateWeightRequest;
use Modules\Weight\Models\Weight;
use Modules\Weight\Repository\WeightRepository;
use Modules\Weight\Transformers\IndexWeightResource;
use Modules\Weight\Transformers\ShowWeightResource;

class WeightController extends Controller
{
      private $weightRepo;

    public function __construct(WeightRepository $weightRepo)
    {
        $this->weightRepo = $weightRepo;
    }

    public function index()
    {

        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexWeightResource::collection($this->weightRepo->index());
    }

    public function store(CreateWeightRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->weightRepo->store($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.weight.store.success', ['title' => $request->title])], 201);
        }

        return response()->json(['message' => __('messages.weight.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Weight $weight)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowWeightResource($weight);

    }

    public function update(Weight $weight, UpdateWeightRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->weightRepo->update($weight, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.weight.update.success', ['title' => $weight->title])], 200);
        }

        return response()->json(['message' => __('messages.weight.update.failed', ['title' => $weight->title])], 500);
    }

    public function destroy($weight)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->weightRepo->delete($weight);
        if ($error === null) {
            return response()->json(['message' => __('messages.weight.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.weight.delete.failed')], 500);
    }
}
