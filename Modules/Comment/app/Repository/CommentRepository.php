<?php

namespace Modules\Comment\Repository;

use Illuminate\Support\Facades\Auth;
use Modules\Comment\Models\Comment;

class CommentRepository implements CommentRepositoryInterface{

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $category = Comment::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('text', 'like', '%'.$req['search'].'%');
            }
        })
        ->orderBy($req['sort'], $req['order'])
        ->paginate($req['limit']);
        
        return $category;

    }

    public function store($product , $request)
    {
        $auth = Auth::id();

        Comment::create([
            'text' => $request->text,
            'product_id' => $product->id,
            'parent_id' => null,
            'user_id' => $auth,
        ]);
            
    }

    public function replay($comment , $request)
    {
        $auth = Auth::id();

        Comment::create([
            'text' => $request->text,
            'product_id' => $comment->product_id,
            'parent_id' => $comment->id,
            'user_id' => $auth,
        ]);

    }

    public function update($comment , $request)
    {

        $comment->update([
            'text' => $request->text ? $request->text : $comment->text,
            'product_id' => $comment->product_id,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
        ]);
    }

    public function delete($comment)
    {
        $comment = Comment::find($comment);

        if (!$comment) {
            return response()->json(['message' => __('messages.category.not_found')], 404);
        }

        $all_comments_parent_id = Comment::pluck('parent_id')->toArray(); //get all parent_id(s)

        if(in_array($comment->id , $all_comments_parent_id)){
            Comment::where('parent_id', $comment->id)->update(['parent_id' => null]);
        }
        $comment->delete();
    }
}