<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
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
            'name' => '腕時計',
            'brand' => 'EMPORIO ARMANI',
            'condition' => '1',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => '15000',
            'image' => 'storage/Armani+Mens+Clock.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'HDD',
            'brand' => 'FUJITSU',
            'condition' => '2',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
            'image' => 'storage/HDD+Hard+Disk.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => '玉ねぎ3束',
            'brand' => '',
            'condition' => '3',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => '300',
            'image' => 'storage/iLoveIMG+d.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => '革靴',
            'brand' => '',
            'condition' => '4',
            'description' => 'クラシックなデザインの革靴',
            'price' => '4000',
            'image' => 'storage/Leather+Shoes+Product+Photo.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'ノートPC',
            'brand' => 'Panasonic',
            'condition' => '1',
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'image' => 'storage/Living+Room+Laptop.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'マイク',
            'brand' => 'MAXIM',
            'condition' => '2',
            'description' => '高音質のレコーディング用マイク',
            'price' => '8000',
            'image' => 'storage/Music+Mic+4632231.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'ショルダーバッグ',
            'brand' => 'NINE WEST',
            'condition' => '3',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
            'image' => 'storage/Purse+fashion+pocket.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'タンブラー',
            'brand' => '',
            'condition' => '4',
            'description' => '使いやすいタンブラー',
            'price' => '500',
            'image' => 'storage/Tumbler+souvenir.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'コーヒーミル',
            'brand' => 'PEUGEOT',
            'condition' => '1',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
            'image' => 'storage/Waitress+with+Coffee+Grinder.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => '1',
            'name' => 'メイクセット',
            'brand' => '',
            'condition' => '2',
            'description' => '便利なメイクアップセット',
            'price' => '2500',
            'image' => 'storage/makeupset.jpg',
        ];
        DB::table('items')->insert($param);
    }
}
