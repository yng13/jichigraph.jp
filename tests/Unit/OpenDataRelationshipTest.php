<?php

namespace Tests\Unit;

use App\Models\Municipality;
use App\Models\Dataset;
use App\Models\OpenData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpenDataRelationshipTest extends TestCase
{
    use RefreshDatabase; // テスト実行後にデータベースをリフレッシュ

    /**
     * A basic feature test example.
     */
    public function test_open_data_belongs_to_municipality()
    {
        // テスト用の自治体データを作成
        $municipality = Municipality::factory()->create();

        // テスト用のデータセットを作成
        $dataset = Dataset::factory()->create();

        // テスト用のオープンデータを作成し、自治体とデータセットを関連付ける
        $openData = OpenData::factory()->create([
            'municipality_id' => $municipality->id,
            'dataset_id' => $dataset->id,
        ]);

        // OpenDataモデルから関連するMunicipalityモデルを取得
        $retrievedMunicipality = $openData->municipality;

        // アサーション: 取得したMunicipalityモデルが作成した自治体データと一致するか
        $this->assertEquals($municipality->id, $retrievedMunicipality->id);
    }

    public function test_open_data_belongs_to_dataset()
    {
        // テスト用の自治体データを作成
        $municipality = Municipality::factory()->create();

        // テスト用のデータセットを作成
        $dataset = Dataset::factory()->create();

        // テスト用のオープンデータを作成し、自治体とデータセットを関連付ける
        $openData = OpenData::factory()->create([
            'municipality_id' => $municipality->id,
            'dataset_id' => $dataset->id,
        ]);

        // OpenDataモデルから関連するDatasetモデルを取得
        $retrievedDataset = $openData->dataset;

        // アサーション: 取得したDatasetモデルが作成したデータセットデータと一致するか
        $this->assertEquals($dataset->id, $retrievedDataset->id);
    }

    public function test_municipality_has_many_open_data()
    {
        // テスト用の自治体データを作成
        $municipality = Municipality::factory()->create();

        // テスト用のオープンデータを複数作成し、同じ自治体に関連付ける
        OpenData::factory(3)->create([
            'municipality_id' => $municipality->id,
        ]);

        // Municipalityモデルから関連するOpenDataモデルの数を取得
        $openDataCount = $municipality->openData->count();

        // アサーション: 取得したOpenDataモデルの数が作成した数と一致するか
        $this->assertEquals(3, $openDataCount);
    }

    public function test_dataset_has_many_open_data()
    {
        // テスト用のデータセットを作成
        $dataset = Dataset::factory()->create();

        // テスト用のオープンデータを複数作成し、同じデータセットに関連付ける
        OpenData::factory(5)->create([
            'dataset_id' => $dataset->id,
        ]);

        // Datasetモデルから関連するOpenDataモデルの数を取得
        $openDataCount = $dataset->openData->count();

        // アサーション: 取得したOpenDataモデルの数が作成した数と一致するか
        $this->assertEquals(5, $openDataCount);
    }
}
