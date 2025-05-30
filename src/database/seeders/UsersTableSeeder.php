<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'name' => 'ファーストユーザー',
            'email' => 'test@test.com',
            'password' => Hash::make('testpass'),
        ];
        DB::table('users')->insert($param);

    }
}
