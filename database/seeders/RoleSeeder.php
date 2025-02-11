<?php

namespace Database\Seeders;

use App\Enums\AvailablePermissionsEnum;
use App\Enums\AvailableRolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AvailableRolesEnum::cases() as $role) {
            Role::query()->firstOrCreate(['name' => $role->value, 'guard_name' => 'api']);
        }

        foreach (AvailablePermissionsEnum::cases() as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission->value, 'guard_name' => 'api']);
        }

        $permissionsByRole = [
            AvailableRolesEnum::MANAGER->value  => AvailableRolesEnum::MANAGER->getPermissions(),
            AvailableRolesEnum::CUSTOMER->value => AvailableRolesEnum::CUSTOMER->getPermissions(),
        ];

        foreach ($permissionsByRole as $roleName => $permissions) {
            $role = Role::query()->firstOrCreate(['name' => $roleName, 'guard_name' => 'api']);
            $role->givePermissionTo($permissions);
        }
    }
}
