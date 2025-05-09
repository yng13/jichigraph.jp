<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportOpenData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:open-data {filename} {dataset_id} {municipality_id} {data_type} {title_column}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import open data from CSV to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = $this->argument('filename');
        $datasetId = $this->argument('dataset_id');
        $municipalityId = $this->argument('municipality_id');
        $dataType = $this->argument('data_type');
        $titleColumn = $this->argument('title_column');

        $path = storage_path('app/private/' . $filename);

        if (!file_exists($path)) {
            $this->error("File not found: $path");
            return 1;
        }

        $file = fopen($path, 'r');

        $header = fgetcsv($file); // ヘッダー行を読み込む

        DB::beginTransaction();

        try {
            while ($row = fgetcsv($file)) {
                $data = array_combine($header, $row); // ヘッダーとデータを組み合わせて連想配列にする

                // 共通カラム
                $insertData = [
                    'municipality_id' => $municipalityId,
                    'dataset_id' => $datasetId,
                    'data_type' => $dataType,
                    'record_identifier' => $data['ID'] ?? null,
                    'title' => $data[$titleColumn] ?? null,
                    'data' => json_encode($data),
                    'created_at' => now(), // 追加
                    'updated_at' => now(),  // 追加
                ];

                // 緯度経度があれば追加
                $latitude = $data['緯度'] ?? null;
                $longitude = $data['経度'] ?? null;

                if (is_numeric($latitude) && is_numeric($longitude)) {
                    $insertData['latitude'] = $latitude;
                    $insertData['longitude'] = $longitude;
                } else {
                    $insertData['latitude'] = null;
                    $insertData['longitude'] = null;
                }

                DB::table('open_data')->insert($insertData);
            }

            DB::commit();

            $this->info('Data imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        fclose($file);

        return 0;
    }
}
