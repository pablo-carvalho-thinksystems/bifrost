<?php

namespace Tests;

use App\Enums\AvailableRolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function getManagerUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole(AvailableRolesEnum::MANAGER);
        return $user;
    }

    public function getCustomerUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole(AvailableRolesEnum::CUSTOMER);
        return $user;
    }
}
