<?php

namespace Modules\Category\Repository;

interface CategoryRepositoryInterface{

    public function index();

    public function store($request);

    public function update($category , $request);

    public function remove_category_image($category);

    public function delete($category);
}
