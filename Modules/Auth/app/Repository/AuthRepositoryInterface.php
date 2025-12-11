<?php

namespace Modules\Auth\Repository;

interface AuthRepositoryInterface
{
    public function register($request);

    public function TwoFactorLoginEmail($request);

    public function TwoFactorLogin($request);

    public function login($request);

    public function ResendCode($request);

    public function logout();
}
