<?php

namespace Modules\Material\Repository;

interface MaterialRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($material, $request);

    public function delete($material);
}
