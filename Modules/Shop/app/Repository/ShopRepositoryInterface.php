<?php

namespace Modules\Shop\Repository;

interface ShopRepositoryInterface
{
    public function index();

    public function update($shop, $request);
}
