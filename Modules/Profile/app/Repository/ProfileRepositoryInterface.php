<?php

namespace Modules\Profile\Repository;

interface ProfileRepositoryInterface
{
    public function update($user, $request);

    public function delete_avatar($user);

    public function change_password($request);
}
