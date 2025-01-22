<?php

namespace Modules\Size\Repository;

interface SizeRepositoryInterface {

    public function index();
    
    public function store($reques);
    
    public function update($size , $request);
    
    public function delete($size);
}