<?php

namespace Modules\Product\Repository;

interface ProductRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($product, $request);

    public function thumbnail($product);

    public function product_iamge($product);

    public function delete($product);
}
