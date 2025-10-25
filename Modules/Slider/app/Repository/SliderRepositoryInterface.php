<?php

namespace Modules\Slider\Repository;

interface SliderRepositoryInterface
{
    public function index();

    public function store($request);

    public function update($request, $slider);

    public function delete($slider);
}
