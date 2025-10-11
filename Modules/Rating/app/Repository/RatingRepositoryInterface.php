<?php

namespace Modules\Rating\Repository;

interface RatingRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($rate, $request);
}
