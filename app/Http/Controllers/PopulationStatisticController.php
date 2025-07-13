<?php

namespace App\Http\Controllers;

use App\Models\PopulationStatistic; // PopulationStatisticモデルをインポート
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PopulationStatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = PopulationStatistic::query();

        // クエリパラメータ 'municipality_code' によるフィルタリング
        if ($request->has('municipality_code')) {
            $query->where('municipality_code', $request->input('municipality_code'));
        }

        // クエリパラメータ 'survey_date' によるフィルタリング
        if ($request->has('survey_date')) {
            $query->where('survey_date', $request->input('survey_date'));
        }

        // クエリパラメータ 'region_code' によるフィルタリング
        if ($request->has('region_code')) {
            $query->where('region_code', $request->input('region_code'));
        }

        // 調査年月日と地域コードでソートして、複数年度のデータ取得時に順番を保証
        $query->orderBy('survey_date')->orderBy('region_code');

        $populationData = $query->get();

        return response()->json($populationData);
    }

    /**
     * Display the specified resource by municipality code and region code.
     *
     * @param  string  $municipalityCode
     * @param  string  $regionCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByMunicipalityAndRegion(string $municipalityCode, string $regionCode): JsonResponse
    {
        $populationData = PopulationStatistic::where('municipality_code', $municipalityCode)
            ->where('region_code', $regionCode)
            ->orderBy('survey_date') // 複数年度のデータを日付順に取得
            ->get();

        if ($populationData->isEmpty()) {
            return response()->json(['message' => 'Population data not found for the specified municipality and region.'], 404);
        }

        return response()->json($populationData);
    }
}
