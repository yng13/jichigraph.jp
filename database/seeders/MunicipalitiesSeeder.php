<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('municipalities')->insert([
            [
                'code' => '112038',
                'name' => '川口市',
                'source' => '川口市',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 他の自治体のデータもここに追加
        ]);
    }
}
