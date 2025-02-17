<?php

namespace Modules\Favorite\Repository;

interface FavoriteRepositoryInterface{

    public function index();

    public function store($request);

    public function remove($favorite);
}