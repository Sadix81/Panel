<?php

namespace Modules\Category\Repository;

interface CategoryRepositoryInterface{

    public function index();

    public function store($request);

    public function update($category , $request);

    public function delete($category);
}
