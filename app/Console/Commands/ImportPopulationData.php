<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PopulationStatistic;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportPopulationData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // type と file に加えて、codeColumnName を引数に追加
    protected $signature = 'import:population {file} {--codeColumn=全国地方公共団体コード}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import population data from a CSV file into the population_statistics table.';

    /**
     * CSVヘッダーとDBカラムのマッピング定義
     * ここに全てのカラムマッピングを記述し、CSVヘッダー名の揺れに対応します。
     */
    protected array $columnMapping = [
        '全国地方公共団体コード' => 'municipality_code',
        '都道府県コード又は市区町村コード' => 'municipality_code', // 新しいヘッダー名を追加
        '地域コード' => 'region_code',
        '地方公共団体名' => 'municipality_name',
        '調査年月日' => 'survey_date',
        '地域名' => 'region_name',
        '総人口' => 'total_population',
        '男性' => 'male_population',
        '女性' => 'female_population',
        '0-4歳の男性' => 'male_0_4',
        '0-4歳の女性' => 'female_0_4',
        '5-9歳の男性' => 'male_5_9',
        '5-9歳の女性' => 'female_5_9',
        '10-14歳の男性' => 'male_10_14',
        '10-14歳の女性' => 'female_10_14',
        '15-19歳の男性' => 'male_15_19',
        '15-19歳の女性' => 'female_15_19',
        '20-24歳の男性' => 'male_20_24',
        '20-24歳の女性' => 'female_20_24',
        '25-29歳の男性' => 'male_25_29',
        '25-29歳の女性' => 'female_25_29',
        '30-34歳の男性' => 'male_30_34',
        '30-34歳の女性' => 'female_30_34',
        '35-39歳の男性' => 'male_35_39',
        '35-39歳の女性' => 'female_35_39',
        '40-44歳の男性' => 'male_40_44',
        '40-44歳の女性' => 'female_40_44',
        '45-49歳の男性' => 'male_45_49',
        '45-49歳の女性' => 'female_45_49',
        '50-54歳の男性' => 'male_50_54',
        '50-54歳の女性' => 'female_50_54',
        '55-59歳の男性' => 'male_55_59',
        '55-59歳の女性' => 'female_55_59',
        '60-64歳の男性' => 'male_60_64',
        '60-64歳の女性' => 'female_60_64',
        '65-69歳の男性' => 'male_65_69',
        '65-69歳の女性' => 'female_65_69',
        '70-74歳の男性' => 'male_70_74',
        '70-74歳の女性' => 'female_70_74',
        '75-79歳の男性' => 'male_75_79',
        '75-79歳の女性' => 'female_75_79',
        '80-84歳の男性' => 'male_80_84',
        '80-84歳の女性' => 'female_80_84',
        '85歳以上の男性' => 'male_85_plus',
        '85歳以上の女性' => 'female_85_plus',
        '世帯数' => 'households_count',
        '備考' => 'remarks',
    ];


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        // オプションでコードカラム名を取得
        $codeColumnNameOption = $this->option('codeColumn');


        if (!Storage::disk('local')->exists($filePath)) {
            $this->error("File not found at: {$filePath}");
            return Command::FAILURE;
        }

        try {
            $csvContent = Storage::disk('local')->get($filePath);
            $utf8CsvContent = mb_convert_encoding($csvContent, 'UTF-8', 'SJIS-win'); // 'SJIS' も試す
            $csv = Reader::createFromString($utf8CsvContent);
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $importedCount = 0;

            $this->info('Starting population data import...');
            $this->info('CSV Headers (after conversion): ' . implode(', ', $csv->getHeader()));

            foreach ($records as $record) {
                // 現在のCSVヘッダー名とDBカラム名の動的マッピング
                $mappedRecord = $this->mapCsvRecord($record);

                // municipality_code の取得とチェックを強化
                $municipalityCode = trim($mappedRecord['municipality_code'] ?? '');
                if (empty($municipalityCode)) {
                    $this->warn("Skipping record due to empty 'municipality_code' for record: " . json_encode($record));
                    continue; // municipality_code が空ならスキップ
                }

                $populationData = [
                    'municipality_code' => $municipalityCode,
                    'municipality_name' => $mappedRecord['municipality_name'] ?? '',
                    'survey_date' => $mappedRecord['survey_date'] ?? '',
                    'region_code' => $mappedRecord['region_code'] ?? '',
                    'region_name' => $mappedRecord['region_name'] ?? '',
                    'total_population' => (int) ($mappedRecord['total_population'] ?? 0),
                    'male_population' => (int) ($mappedRecord['male_population'] ?? 0),
                    'female_population' => (int) ($mappedRecord['female_population'] ?? 0),
                    'male_0_4' => (int) ($mappedRecord['male_0_4'] ?? 0),
                    'female_0_4' => (int) ($mappedRecord['female_0_4'] ?? 0),
                    'male_5_9' => (int) ($mappedRecord['male_5_9'] ?? 0),
                    'female_5_9' => (int) ($mappedRecord['female_5_9'] ?? 0),
                    'male_10_14' => (int) ($mappedRecord['male_10_14'] ?? 0),
                    'female_10_14' => (int) ($mappedRecord['female_10_14'] ?? 0),
                    'male_15_19' => (int) ($mappedRecord['male_15_19'] ?? 0),
                    'female_15_19' => (int) ($mappedRecord['female_15_19'] ?? 0),
                    'male_20_24' => (int) ($mappedRecord['male_20_24'] ?? 0),
                    'female_20_24' => (int) ($mappedRecord['female_20_24'] ?? 0),
                    'male_25_29' => (int) ($mappedRecord['male_25_29'] ?? 0),
                    'female_25_29' => (int) ($mappedRecord['female_25_29'] ?? 0),
                    'male_30_34' => (int) ($mappedRecord['male_30_34'] ?? 0),
                    'female_30_34' => (int) ($mappedRecord['female_30_34'] ?? 0),
                    'male_35_39' => (int) ($mappedRecord['male_35_39'] ?? 0),
                    'female_35_39' => (int) ($mappedRecord['female_35_39'] ?? 0),
                    'male_40_44' => (int) ($mappedRecord['male_40_44'] ?? 0),
                    'female_40_44' => (int) ($mappedRecord['female_40_44'] ?? 0),
                    'male_45_49' => (int) ($mappedRecord['male_45_49'] ?? 0),
                    'female_45_49' => (int) ($mappedRecord['female_45_49'] ?? 0),
                    'male_50_54' => (int) ($mappedRecord['male_50_54'] ?? 0),
                    'female_50_54' => (int) ($mappedRecord['female_50_54'] ?? 0),
                    'male_55_59' => (int) ($mappedRecord['male_55_59'] ?? 0),
                    'female_55_59' => (int) ($mappedRecord['female_55_59'] ?? 0),
                    'male_60_64' => (int) ($mappedRecord['male_60_64'] ?? 0),
                    'female_60_64' => (int) ($mappedRecord['female_60_64'] ?? 0),
                    'male_65_69' => (int) ($mappedRecord['male_65_69'] ?? 0),
                    'female_65_69' => (int) ($mappedRecord['female_65_69'] ?? 0),
                    'male_70_74' => (int) ($mappedRecord['male_70_74'] ?? 0),
                    'female_70_74' => (int) ($mappedRecord['female_70_74'] ?? 0),
                    'male_75_79' => (int) ($mappedRecord['male_75_79'] ?? 0),
                    'female_75_79' => (int) ($mappedRecord['female_75_79'] ?? 0),
                    'male_80_84' => (int) ($mappedRecord['male_80_84'] ?? 0),
                    'female_80_84' => (int) ($mappedRecord['female_80_84'] ?? 0),
                    'male_85_plus' => (int) ($mappedRecord['male_85_plus'] ?? 0),
                    'female_85_plus' => (int) ($mappedRecord['female_85_plus'] ?? 0),
                    'households_count' => (int) ($mappedRecord['households_count'] ?? 0),
                    'remarks' => $mappedRecord['remarks'] ?? null,
                ];

                // INSERT文の実行前に重複チェック (ユニーク制約でほとんど防げるが、念のため)
                $existingRecord = PopulationStatistic::where('municipality_code', $populationData['municipality_code'])
                    ->where('survey_date', $populationData['survey_date'])
                    ->where('region_code', $populationData['region_code'])
                    ->first();

                if ($existingRecord) {
                    $this->warn("Skipping duplicate record for municipality_code: {$populationData['municipality_code']}, survey_date: {$populationData['survey_date']}, region_code: {$populationData['region_code']}");
                    continue;
                }


                PopulationStatistic::create($populationData);
                $importedCount++;
            }

            $this->info("Successfully imported {$importedCount} population records.");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("An error occurred during import: " . $e->getMessage());
            $this->error("Error details: " . $e->getFile() . " on line " . $e->getLine());
            return Command::FAILURE;
        }
    }

    /**
     * CSVレコードをDBカラム名にマッピングする
     * @param array $record CSVから読み込んだ1行のデータ
     * @return array DBカラム名にマッピングされたデータ
     */
    protected function mapCsvRecord(array $record): array
    {
        $mapped = [];
        foreach ($this->columnMapping as $csvHeader => $dbColumn) {
            // CSVヘッダー名と $record のキーが一致すればその値を、なければnull
            $mapped[$dbColumn] = $record[$csvHeader] ?? null;

            // '総人口' や '男性' など、カンマ区切りの数値がある場合は除去して整数に変換
            if (in_array($dbColumn, [
                'total_population', 'male_population', 'female_population',
                'male_0_4', 'female_0_4', 'male_5_9', 'female_5_9', 'male_10_14', 'female_10_14',
                'male_15_19', 'female_15_19', 'male_20_24', 'female_20_24', 'male_25_29', 'female_25_29',
                'male_30_34', 'female_30_34', 'male_35_39', 'female_35_39', 'male_40_44', 'female_40_44',
                'male_45_49', 'female_45_49', 'male_50_54', 'female_50_54', 'male_55_59', 'female_55_59',
                'male_60_64', 'female_60_64', 'male_65_69', 'female_65_69', 'male_70_74', 'female_70_74',
                'male_75_79', 'female_75_79', 'male_80_84', 'female_80_84', 'male_85_plus', 'female_85_plus',
                'households_count'
            ])) {
                $mapped[$dbColumn] = str_replace(',', '', (string) ($mapped[$dbColumn] ?? '0'));
                $mapped[$dbColumn] = (int) $mapped[$dbColumn];
            }
        }
        return $mapped;
    }
}
