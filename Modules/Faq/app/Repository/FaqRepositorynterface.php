<?php

namespace Modules\Faq\app\Repository;

interface FaqRepositorynterface
{
    public function index();

    public function store($request);

    public function update($faq, $request);

    public function delete($faq);
}
