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
            'name'     => 'pablo.carvalho',
            'password' => bcrypt('Valid123.'),
        ]);

        $manager->assignRole(AvailableRolesEnum::MANAGER);
    }
}