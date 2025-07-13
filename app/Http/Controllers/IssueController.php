<?php

namespace App\Http\Controllers;

use App\Models\Issue;

// Issueモデルをインポート
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

// バリデーターをインポート

class IssueController extends Controller
{
    /**
     * Store a newly created issue in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // 1. リクエストデータのバリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'municipality_code' => 'required|string|max:20', // 全国地方公共団体コードは必須
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'image_url' => 'nullable|url|max:2048', // 画像URLはURL形式で最大2MB
            // 'user_id' は現在nullableで、認証機能導入後に考慮
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity はバリデーションエラーを示すHTTPステータスコード
        }

        // 2. 課題の作成と保存
        try {
            $issue = Issue::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $request->user_id, // 認証機能がないため、現状はnullかフロントから渡された値
                'status' => 'open', // デフォルトは'open'
                'municipality_code' => $request->municipality_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'image_url' => $request->image_url,
            ]);

            return response()->json([
                'message' => 'Issue created successfully.',
                'issue' => $issue
            ], 201); // 201 Created はリソースの新規作成を示すHTTPステータスコード

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create issue.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display a listing of issues. (Placeholder for now)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Issue::query();

        // クエリパラメータによるフィルタリング（例: municipality_code, status）
        if ($request->has('municipality_code')) {
            $query->where('municipality_code', $request->input('municipality_code'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $issues = $query->latest()->get(); // 最新の投稿から取得

        return response()->json($issues);
    }
}
