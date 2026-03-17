<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'client',   'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin',    'guard_name' => 'api']);
    }
}
