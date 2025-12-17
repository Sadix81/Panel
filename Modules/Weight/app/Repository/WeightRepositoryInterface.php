<?php

namespace Modules\Weight\Repository;

interface WeightRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($weight, $request);

    public function delete($weight);
}
