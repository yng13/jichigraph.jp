<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('datasets')->insert([
            [
                'name' => '川口市避難場所一覧',
                'description' => '川口市内の避難場所に関する情報を提供します。',
                'source' => '川口市',
                'publication_date' => '2024-04-26',
                'update_frequency' => '随時',
                'data_source_type' => 'web',
                'data_source_url' => 'https://www.city.kawaguchi.lg.jp/opendata/hinanbasho.csv', // 例
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '川口市AED設置場所一覧',
                'description' => '川口市内のAED設置場所に関する情報を提供します。',
                'source' => '川口市',
                'publication_date' => '2024-04-26',
                'update_frequency' => '随時',
                'data_source_type' => 'web',
                'data_source_url' => 'https://www.city.kawaguchi.lg.jp/opendata/aed.csv', // 例
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '川口市公共施設一覧',
                'description' => '川口市内の公共施設に関する情報を提供します。',
                'source' => '川口市',
                'publication_date' => '2024-04-26',
                'update_frequency' => '随時',
                'data_source_type' => 'web',
                'data_source_url' => 'https://www.city.kawaguchi.lg.jp/opendata/public_facility.csv', // 例
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '川口市学校一覧',
                'description' => '川口市内の学校に関する情報を提供します。',
                'source' => '川口市',
                'publication_date' => '2024-04-26',
                'update_frequency' => '随時',
                'data_source_type' => 'web',
                'data_source_url' => 'https://www.city.kawaguchi.lg.jp/opendata/school.csv', // 例
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '川口市人口統計データ',
                'description' => '川口市の人口統計データを提供します。',
                'source' => '川口市',
                'publication_date' => '2024-04-26',
                'update_frequency' => '毎月',
                'data_source_type' => 'local',
                'data_source_url' => '/path/to/population_data.csv', // 例
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 他のデータセットもここに追加
        ]);
    }
}
