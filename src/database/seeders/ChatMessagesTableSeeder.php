<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatMessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'dealing_id' => '1',
            'user_id' => '1',
            'message' => 'テスト用',
            'created_at' => Carbon::now()->subHours(5),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '1',
            'user_id' => '2',
            'message' => 'test2 test',
            'created_at' => Carbon::now()->subHours(4),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '1',
            'user_id' => '1',
            'message' => 'for image test',
            'image' => 'storage/pineapple.png',
            'created_at' => Carbon::now()->subHours(3),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '1',
            'user_id' => '2',
            'message' => 'テスト２テスト２テスト２テスト２テスト２テスト２テスト２テスト２テスト２テスト２',
            'created_at' => Carbon::now()->subHours(2),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '1',
            'user_id' => '1',
            'message' => 'for edit test',
            'created_at' => Carbon::now()->subHours(1),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '2',
            'user_id' => '3',
            'message' => 'Hello!',
            'created_at' => Carbon::now()->subHours(6),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '2',
            'user_id' => '1',
            'message' => 'Thank you for your contact!',
            'created_at' => Carbon::now()->subHours(5),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '4',
            'user_id' => '1',
            'message' => '値段交渉は可能でしょうか？',
            'created_at' => Carbon::now()->subHours(8),
        ];
        DB::table('chat_messages')->insert($param);

        $param =[
            'dealing_id' => '4',
            'user_id' => '2',
            'message' => 'お問い合わせありがとうございます。条件次第で検討いたします。まずはご要望を具体的にお聞かせいただけますでしょうか？',
            'created_at' => Carbon::now()->subHours(7),
        ];
        DB::table('chat_messages')->insert($param);

    }
}
