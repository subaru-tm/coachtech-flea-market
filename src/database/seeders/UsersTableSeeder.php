<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
            'name' => 'テストユーザー１',
            'email' => 'test1@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('test1pass'),
            'post_code' => '111-1111',
            'address' => 'テスト１県テスト１市テスト１区１－１－１',
            'building' => 'テスト１ビル１０１',
            'image' => 'storage/test.png'
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー２',
            'email' => 'test2@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('test2pass'),
            'post_code' => '222-2222',
            'address' => 'テスト２県テスト２市テスト２区２－２－２',
            'building' => 'テスト２ビル２０２',
            'image' => 'storage/test2.png'
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー３',
            'email' => 'test3@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('test3pass'),
            'post_code' => '333-3333',
            'address' => 'テスト３県テスト３市テスト３区３－３－３',
            'building' => 'テスト３ビル３０３',
        ];
        DB::table('users')->insert($param);
    }
}
