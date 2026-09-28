<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $dataRole = [
            [
                'id' => 1,
                'name' => 'admin',
            ],
            [
                'id' => 2,
                'name' => 'user',
            ],
            [
                'id' => 3,
                'name' => 'manager',
            ],
        ];
        DB::table('roles')->insert($dataRole);

        $dataUser = [
            [
                'id' => 1,
                'name' => Str::random(10),
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'username' => 'admin',
                'phone' => '234',
                'role_id' => 1,
            ],
            [
                'id' => 2,
                'name' => Str::random(10),
                'email' => 'user@gmail.com',
                'password' => Hash::make('123456'),
                'username' => 'user',
                'phone' => '345',
                'role_id' => 2,
            ],
            [
                'id' => 3,
                'name' => Str::random(10),
                'email' => 'manager@gmail.com',
                'password' => Hash::make('123456'),
                'username' => 'manager',
                'phone' => '345',
                'role_id' => 3,
            ],
        ];
        DB::table('users')->insert($dataUser);

        $dataLevel = [
            [
                'id' => 1,
                'name' => 'Cử nhân',
            ],
            [
                'id' => 2,
                'name' => 'Thạc sĩ',
            ],
            [
                'id' => 3,
                'name' => 'Tiến sĩ',
            ],
            [
                'id' => 4,
                'name' => 'Trưởng khoa',
            ],
            [
                'id' => 5,
                'name' => 'Phó giáo sư',
            ],
            [
                'id' => 6,
                'name' => 'Giáo sư',
            ],
        ];
        DB::table('level')->insert($dataLevel);
    }
}
