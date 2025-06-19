<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MylistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => '1',
            'item_id' => '5',
            'nice_flug' => '1',
            'comment' => 'test1comment',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '6',
            'nice_flug' => '1',
            'comment' => 'test1comment',
        ];
        DB::table('mylists')->insert($param);


        $param = [
            'user_id' => '1',
            'item_id' => '7',
            'nice_flug' => '1',
            'comment' => '',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '8',
            'nice_flug' => '1',
            'comment' => '',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '1',
            'item_id' => '10',
            'nice_flug' => '0',
            'comment' => 'test1comment',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '7',
            'nice_flug' => '1',
            'comment' => 'test2comment',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '2',
            'item_id' => '1',
            'nice_flug' => '0',
            'comment' => 'test2comment',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '3',
            'item_id' => '4',
            'nice_flug' => '1',
            'comment' => 'test3comment',
        ];
        DB::table('mylists')->insert($param);

        $param = [
            'user_id' => '3',
            'item_id' => '1',
            'nice_flug' => '1',
            'comment' => '',
        ];
        DB::table('mylists')->insert($param);

    }
}
