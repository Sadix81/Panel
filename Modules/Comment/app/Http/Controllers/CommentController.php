<?php

namespace Modules\Comment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Http\Requests\CreateCommentRequest;
use Modules\Comment\Http\Requests\UpdateCommentRequest;
use Modules\Comment\Models\Comment;
use Modules\Comment\Repository\CommentRepository;
use Modules\Comment\Transformers\IndexCommentResource;
use Modules\Comment\Transformers\ShowCommentResource;
use Modules\Product\Models\Product;

class CommentController extends Controller
{
    private $commentRepo;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function index()
    {
        return IndexCommentResource::collection($this->commentRepo->index());
    }

    public function store(CreateCommentRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->commentRepo->store($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.comment.store.success')], 201);
        }

        return response()->json(['message' => __('messages.comment.store.failed')], 500);
    }

    public function show(Comment $comment)
    {
        return new ShowCommentResource($comment);
    }

    public function replay(Comment $comment, CreateCommentRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if (! $comment) {
            return response()->json(['message' => 'product not found'], 404);
        }

        if ($comment->status == 0) {
            return response()->json(['message' => 'امکان ثبت نظر وجود ندارد']);
        }

        if ((int) $comment->parent_id > $comment->id) {
            return response()->json(['message' => __('messages.comment.update.parent_id.failed')], 400);
        }

        $error = $this->commentRepo->replay($comment, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.comment.replay.store.success')], 201);
        }

        return response()->json(['message' => __('messages.comment.replay.store.failed')], 500);
    }

    public function update(Comment $comment, UpdateCommentRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ((int) $comment->parent_id > $comment->id) {
            return response()->json(['message' => __('messages.comment.update.parent_id.failed')], 400);
        }

        $error = $this->commentRepo->update($comment, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.comment.update.success', ['name' => $comment->name])], 200);
        }

        return response()->json(['message' => __('messages.comment.update.failed', ['name' => $comment->name])], 500);
    }

    public function destroy($comment)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->commentRepo->delete($comment);
        if ($error === null) {
            return response()->json(['message' => __('messages.comment.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.comment.delete.failed')], 500);
    }
}
