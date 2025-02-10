<?php

namespace Modules\Discount\Repository;


interface DiscountRepositoryInterface {

    public function index();

    public function store($request);

    public function update($discount , $request);

    public function allprductsdiscount($discount ,$product , $request);

    public function delete($discount);
}