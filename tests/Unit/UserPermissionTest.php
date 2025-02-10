<?php

namespace Tests\Unit;


use App\Enums\AvailableRolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testUserManagerPermissions(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(AvailableRolesEnum::MANAGER);

        $managerPermissions = AvailableRolesEnum::MANAGER->getPermissions();

        foreach ($managerPermissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission));
        }
    }

    public function testUserCustomerPermissions(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(AvailableRolesEnum::CUSTOMER);

        $customerPermissions = AvailableRolesEnum::CUSTOMER->getPermissions();

        foreach ($customerPermissions as $permission) {
            $this->assertTrue($user->hasPermissionTo($permission));
        }
    }

}
