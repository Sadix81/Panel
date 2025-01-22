<?php

namespace Modules\Color\Repository;

interface ColorRepositoryInterface{

    public function index();

    public function store($request);

    public function update($color , $request);
    
    public function delete($color);
}