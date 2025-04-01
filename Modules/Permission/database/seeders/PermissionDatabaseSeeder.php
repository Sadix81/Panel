<?php

namespace Modules\Permission\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'SuperAdmin',
            'guard_name' => 'api',
        ]);

        $user = User::find(1);
        if ($user && !$user->hasRole('SuperAdmin')) {
            $user->assignRole($role);
        }
    }
}
