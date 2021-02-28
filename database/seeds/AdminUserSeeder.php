<?php

use Illuminate\Database\Seeder;
use App\Model\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'userType'  => 'SuperAdmin',
            'name'      => 'Super Admin',
            'email'     => 'admin@yopmail.com',
            'password'  =>  bcrypt('admin@123'),
            'showPassword'  => 'admin@123',
            'isActive'  => 1
        ]);

        User::create([
            'userType'  => 'Admin',
            'name'      => 'Admin',
            'email'     => 'admin@yopmail.com',
            'password'  =>  bcrypt('admin@123'),
            'showPassword'  => 'admin@123',
            'isActive'  => 1
        ]);
    }
}
