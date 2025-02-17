<?php

namespace Modules\Favorite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Favorite\Http\Requests\CreateFavoriteRequest;
use Modules\Favorite\Http\Requests\RemoveFavoriteRequest;
use Modules\Favorite\Models\Favorite;
use Modules\Favorite\Repository\FavoriteRepository;
use Modules\Favorite\Transformers\IndexFavoriteResource;

class FavoriteController extends Controller
{
   private $favoriteRepo;

   public function __construct(FavoriteRepository $favoriteRepo)
   {
        $this->favoriteRepo = $favoriteRepo;
   }

   public function index()
   {
        return IndexFavoriteResource::collection($this->favoriteRepo->index());
   }

   public function store(CreateFavoriteRequest $request){

    $user = Auth::user();

    if (! $user) {
        return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
    }

    $error = $this->favoriteRepo->store($request);
    if($error === 'exist'){
        return response()->json(['message' => __('messages.favorite.store.success')], 201);
    }
    if($error === null){
        return response()->json(['message' => __('messages.favorite.store.success')], 201);
    }

    return response()->json(['message' => __('messages.favorite.store.failed')], 500);

   }
   
   public function remove(Favorite $favorite){
    $user = Auth::user();

    if (! $user) {
        return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
    }

    if($favorite->user_id != $user->id){

        return response()->json(['message' => '!عدم دسترسی']);
    }

    $error = $this->favoriteRepo->remove($favorite);
    if($error === null){
        return response()->json(['message' => __('messages.favorite.remove.success')], 201);
    }

    return response()->json(['message' => __('messages.favorite.remove.failed')], 500);

    
   }

}
