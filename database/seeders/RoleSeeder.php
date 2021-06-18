<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'id' => 1,
            'firstname' => 'Superadmin',
            'lastname' => 'Unface',
            'email' => 'admin@unface.com',
            'phone_number' => '085887028342',
            'email_verified_at' => '2021-04-13 17:46:46',
            'password' => Hash::make('123123123'),
            'photo' => null,
            'remember_token' => Str::random(16),
            'created_at' => Date::now(),
            'updated_at' => Date::now()
        ]);

        $user = User::create([
            'id' => 2,
            'firstname' => 'Muhammad Rizky',
            'lastname' => 'Firdaus',
            'email' => 'usuirizky@gmail.com',
            'phone_number' => '085899929431',
            'email_verified_at' => '2021-04-13 17:46:46',
            'password' => Hash::make('123123123'),
            'photo' => null,
            'remember_token' => Str::random(16),
            'created_at' => Date::now(),
            'updated_at' => Date::now()
        ]);

        Role::create([
            'id' => 1,
            'name' => 'superadmin',
            'guard_name' => 'api'
        ]);

        Role::create([
            'id' => 2,
            'name' => 'customer',
            'guard_name' => 'api'
        ]);

        $roleAdmin = User::find(1);
        $roleUser = User::find(2);

        $roleAdmin->syncRoles([Role::find(2), Role::find(1)]);
        $roleUser->assignRole(Role::find(2));
    }
}
