<?php

namespace Modules\Faq\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\app\Repository\FaqRepository;
use Modules\Faq\Http\Requests\FaqRequest;
use Modules\Faq\Models\Faq;
use Modules\Faq\Transformers\IndexFaqResource;
use Modules\Faq\Transformers\ShowFaqResource;

class FaqController extends Controller
{
    private $faqRepo;

    public function __construct(FaqRepository $faqRepo)
    {
        $this->faqRepo = $faqRepo;
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexFaqResource::collection($this->faqRepo->index());
    }

    public function store(FaqRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if (! $user->hasRole('SuperAdmin')) {
            return response()->json(['message' => 'عدم دسترسی'], 403);
        }

        $error = $this->faqRepo->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.faq.store.success', ['question' => $request->question])], 201);
        }

        return response()->json(['message' => __('messages.faq.store.failed', ['question' => $request->question])], 500);
    }

    public function show(Faq $faq)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowFaqResource($faq);

    }

    public function update(Faq $faq, FaqRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if (! $user->hasRole('SuperAdmin')) {
            return response()->json(['message' => 'عدم دسترسی'], 403);
        }
        
        $error = $this->faqRepo->update($faq, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.faq.update.success', ['question' => $faq->question])], 200);
        }

        return response()->json(['message' => __('messages.faq.update.failed', ['question' => $faq->question])], 500);
    }

    public function destroy($faq)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->faqRepo->delete($faq);
        if ($error === null) {
            return response()->json(['message' => __('messages.faq.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.faq.delete.failed')], 500);
    }
}
