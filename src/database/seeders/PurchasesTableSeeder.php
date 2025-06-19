<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'user_id' => '1',
            'item_id' => '4',
            'payment_method' => '1',
            'shipping_post_code' => '111-1111',
            'shipping_address' => 'test1 address',
            'shipping_building' => 'test1 building',
        ];
        DB::table('purchases')->insert($param);

        $param =[
            'user_id' => '2',
            'item_id' => '7',
            'payment_method' => '2',
            'shipping_post_code' => '222-2222',
            'shipping_address' => 'test2 address',
            'shipping_building' => 'test2 building',
        ];
        DB::table('purchases')->insert($param);

        $param =[
            'user_id' => '3',
            'item_id' => '2',
            'payment_method' => '1',
            'shipping_post_code' => '333-3333',
            'shipping_address' => 'test3 address',
            'shipping_building' => 'test3 building',
        ];
        DB::table('purchases')->insert($param);
    }
}
