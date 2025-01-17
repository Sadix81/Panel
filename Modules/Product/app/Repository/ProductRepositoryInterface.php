<?php

namespace Modules\Product\Repository;

interface ProductRepositoryInterface {

    public function index();

    public function store($request);

    public function update($product , $request);

    public function delete($product);

    public function restore($product);
}