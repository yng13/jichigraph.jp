<?php

namespace App\Http\Controllers;

use App\Models\PointOfInterest;

// PointOfInterestモデルをインポート
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// JsonResponseを使用する場合

class PointOfInterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = PointOfInterest::query();

        // クエリパラメータ 'municipality_code' によるフィルタリング
        if ($request->has('municipality_code')) {
            $query->where('municipality_code', $request->input('municipality_code'));
        }

        // クエリパラメータ 'type' によるフィルタリング
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $pois = $query->get();

        return response()->json($pois);
    }

    /**
     * Display a listing of the resource filtered by type.
     * (Optional: if you prefer URL-based type filtering)
     *
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexByType(string $type): JsonResponse
    {
        $pois = PointOfInterest::where('type', $type)->get();

        return response()->json($pois);
    }

    // 必要に応じて、詳細表示 (show), 作成 (store), 更新 (update), 削除 (destroy) メソッドを追加
}
