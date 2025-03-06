<?php

namespace Modules\ForgotPassword\Repository;

interface ForgotPasswordRepositorynterface
{
    public function password($request);

    public function ChangePassword($user, $request);
}
