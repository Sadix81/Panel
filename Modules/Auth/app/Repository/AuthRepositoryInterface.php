<?php

namespace Modules\Auth\Repository;

interface AuthrepositoryInterface{

    public function register($request);

    public function login($request);

    public function ResendCode($request);

    public function logout($request);
}