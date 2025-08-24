<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'item_id' => '1',
            'user_id' => '2',
            'status' => 'dealing',
        ];
        DB::table('dealings')->insert($param);

        $param =[
            'item_id' => '3',
            'user_id' => '3',
            'status' => 'dealing',
        ];
        DB::table('dealings')->insert($param);

        $param =[
            'item_id' => '2',
            'user_id' => '3',
            'status' => 'completed',
            'customer_rating' => '5',
        ];
        DB::table('dealings')->insert($param);

        $param =[
            'item_id' => '8',
            'user_id' => '1',
            'status' => 'dealing',
        ];
        DB::table('dealings')->insert($param);
        
        $param =[
            'item_id' => '9',
            'user_id' => '1',
            'status' => 'completed',
            'customer_rating' => '4',
            'seller_rating' => '2',
        ];
        DB::table('dealings')->insert($param);
        
    }
}
