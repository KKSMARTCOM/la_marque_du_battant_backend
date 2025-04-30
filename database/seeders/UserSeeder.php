<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'lastname' => 'Doe',
                'firstname' => 'John',
                'avatar' => null,
                'email' => 'john.doe@example.com',
                'password' => Hash::make('Password@123'),
                'birthday' => '1985-05-15',
                'role' => 'client',
                'provider' => null,
                'provider_id' => null,
            ],
            [
                'lastname' => 'Admin',
                'firstname' => 'Super',
                'avatar' => null,
                'email' => 'super.admin@example.com',
                'password' => Hash::make('Superpassword@123'),
                'birthday' => '1990-01-10',
                'role' => 'super-admin',
                'provider' => null,
                'provider_id' => null,
            ],
            [
                'lastname' => 'Smith',
                'firstname' => 'Anna',
                'avatar' => null,
                'email' => 'anna.smith@example.com',
                'password' => Hash::make('Adminpassword@123'),
                'birthday' => '1995-07-20',
                'role' => 'admin',
                'provider' => 'google',
                'provider_id' => Str::random(10),
            ],
            [
                'lastname' => 'User',
                'firstname' => 'Regular',
                'avatar' => null,
                'email' => 'regular.user@example.com',
                'password' => Hash::make('Userpassword@123'),
                'birthday' => '2000-11-05',
                'role' => 'user',
                'provider' => 'facebook',
                'provider_id' => Str::random(10),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
