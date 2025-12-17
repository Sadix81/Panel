<?php

namespace Modules\Material\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Material\Http\Requests\CreateMaterialRequest;
use Modules\Material\Http\Requests\UpdateMaterialRequest;
use Modules\Material\Models\Material;
use Modules\Material\Repository\MaterialRepository;
use Modules\Material\Transformers\IndexMaterialResource;
use Modules\Material\Transformers\ShowMaterialResource;

class MaterialController extends Controller
{
    private $materialtRepo;

    public function __construct(MaterialRepository $materialRepo)
    {
        $this->materialtRepo = $materialRepo;
    }

    public function index()
    {

        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexMaterialResource::collection($this->materialtRepo->index());
    }

    public function store(CreateMaterialRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->materialtRepo->store($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.material.store.success', ['title' => $request->title])], 201);
        }

        return response()->json(['message' => __('messages.material.store.failed', ['title' => $request->title])], 500);
    }

    public function show(Material $material)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowMaterialResource($material);

    }

    public function update(Material $material, UpdateMaterialRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->materialtRepo->update($material, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.material.update.success', ['title' => $material->title])], 200);
        }

        return response()->json(['message' => __('messages.material.update.failed', ['title' => $material->title])], 500);
    }

    public function destroy($material)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->materialtRepo->delete($material);
        if ($error === null) {
            return response()->json(['message' => __('messages.material.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.material.delete.failed')], 500);
    }
}
