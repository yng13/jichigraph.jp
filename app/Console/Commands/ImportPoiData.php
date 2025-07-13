<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PointOfInterest;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportPoiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:poi {type} {file}'; // {type}でデータの種類を指定

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Point of Interest data from a CSV file into the points_of_interest table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type'); // データタイプ (例: AED, EvacuationShelter)
        $filePath = $this->argument('file');

        // Laravelが実際に探しているパスを出力（デバッグ用）
        $fullPath = Storage::disk('local')->path($filePath);
        $this->info("Laravel is looking for file at: {$fullPath}");

        // CSVファイルが存在するか確認
        if (!Storage::disk('local')->exists($filePath)) {
            $this->error("File not found at: {$filePath}");
            return Command::FAILURE;
        }

        try {
            $csv = Reader::createFromString(Storage::disk('local')->get($filePath));
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $importedCount = 0;

            $this->info("Starting {$type} data import from {$filePath}...");

            foreach ($records as $record) {
                $poiData = $this->mapCsvRecordToPoi($type, $record);

                if ($poiData) {
                    PointOfInterest::create($poiData);
                    $importedCount++;
                } else {
                    $this->warn("Skipping record due to unhandled type or mapping error for type: {$type}");
                }
            }

            $this->info("Successfully imported {$importedCount} {$type} records.");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("An error occurred during import: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Maps a CSV record to PointOfInterest data based on type.
     *
     * @param string $type
     * @param array $record
     * @return array|null
     */
    protected function mapCsvRecordToPoi(string $type, array $record): ?array
    {
        // ここにタイプごとのマッピングロジックを実装
        // 今はAEDのみに対応。他のタイプが追加されたらここを拡張する
        if ($type === 'AED') {
            // '小児対応設備の有無' と '外部利用不可' の変換
            $childFriendly = (isset($record['小児対応設備の有無']) && $record['小児対応設備の有無'] === '有');
            $externalUseForbidden = (isset($record['外部利用不可']) && ($record['外部利用不可'] === 'TRUE' || $record['外部利用不可'] === '有')); // CSVによっては'有'もありうる

            $poiData = [
                'municipality_code' => $record['全国地方公共団体コード'] ?? null,
                'municipality_name' => $record['地方公共団体名'] ?? null,
                'type' => $type, // 引数で受け取ったタイプを設定
                'name' => $record['名称'] ?? '不明な地点',
                'name_kana' => $record['名称_カナ'] ?? null,
                'name_english' => $record['名称_英字'] ?? null,
                'address' => $record['所在地_連結表記'] ?? null,
                'prefecture' => $record['所在地_都道府県'] ?? null,
                'city' => $record['所在地_市区町村'] ?? null,
                'town' => $record['所在地_町字'] ?? null,
                'block_number' => $record['所在地_番地以下'] ?? null,
                'building_name' => $record['建物名等(方書)'] ?? null,
                'latitude' => (double)($record['緯度'] ?? 0.0),
                'longitude' => (double)($record['経度'] ?? 0.0),
                'phone_number' => $record['電話番号'] ?? null,
                'contact_email' => $record['連絡先メールアドレス'] ?? null,
                'url' => $record['URL'] ?? null,
                'remarks' => $record['備考'] ?? null,
            ];

            $details = [
                'height_type' => $record['高度の種別'] ?? null,
                'height_value' => (double)($record['高度の値'] ?? 0.0),
                'installation_position' => $record['設置位置'] ?? null,
                'extension_number' => $record['内線番号'] ?? null,
                'contact_form_url' => $record['連絡先FormURL'] ?? null,
                'contact_remarks' => $record['連絡先備考（その他、SNSなど）'] ?? null,
                'postal_code' => $record['郵便番号'] ?? null,
                'corporate_number' => $record['法人番号'] ?? null,
                'organization_name' => $record['団体名'] ?? null,
                'available_days' => $record['利用可能曜日'] ?? null,
                'start_time' => $record['開始時間'] ?? null,
                'end_time' => $record['終了時間'] ?? null,
                'availability_remarks' => $record['利用可能日時特記事項'] ?? null,
                'child_friendly' => $childFriendly,
                'image1_url' => $record['画像1'] ?? null,
                'image1_license' => $record['画像1_ライセンス'] ?? null,
                'image2_url' => $record['画像2'] ?? null,
                'image2_license' => $record['画像2_ライセンス'] ?? null,
                'external_use_forbidden' => $externalUseForbidden,
            ];
            $poiData['details'] = json_encode($details, JSON_UNESCAPED_UNICODE);

            return $poiData;
        }

        // 未知のタイプの場合
        return null;
    }
}
