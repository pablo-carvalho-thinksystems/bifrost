<?php

namespace Database\Seeders;

use App\Enums\AvailableRolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::query()->firstOrCreate(['email' => 'pablo.carvalho@bifrost.com'], [
            'name'     => 'Pablo Carvalho',
            'password' => bcrypt('Valid123.'),
        ]);

        $customer = User::query()->firstOrCreate(['email' => 'samuel.carvalho@bifrost.com'], [
            'name'     => 'Samuel Carvalho',
            'password' => bcrypt('Valid123.'),
        ]);

        $customer->assignRole(AvailableRolesEnum::CUSTOMER);
        $manager->assignRole(AvailableRolesEnum::MANAGER);
    }
}