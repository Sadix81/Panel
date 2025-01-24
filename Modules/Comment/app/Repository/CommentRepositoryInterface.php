<?php

namespace Modules\Comment\Repository;

interface CommentRepositoryInterface{

    public function index();

    public function store($product , $request);

    public function replay($comment , $request);

    public function update($comment , $request);

    public function delete($comment);
}