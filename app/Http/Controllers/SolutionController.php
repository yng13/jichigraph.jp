<?php

namespace App\Http\Controllers;

use App\Models\Solution; // Solutionモデルをインポート
use App\Models\Issue;    // Issueモデルをインポート（関連付けのため）
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SolutionController extends Controller
{
    /**
     * Store a newly created solution in storage for a specific issue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Issue $issue): JsonResponse
    {
        // 1. リクエストデータのバリデーション
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:10', // 解決策の内容は必須で最低10文字
            // 'user_id' は現在nullableで、認証機能導入後に考慮
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // 2. 解決策の作成と保存
        try {
            $solution = $issue->solutions()->create([ // Issueモデルのリレーションを使って作成
                'user_id' => $request->user_id, // 認証機能がないため、現状はnullかフロントから渡された値
                'content' => $request->content,
                'status' => 'pending', // デフォルトは'pending'
                'votes' => 0, // デフォルトは0票
            ]);

            return response()->json([
                'message' => 'Solution created successfully.',
                'solution' => $solution
            ], 201); // 201 Created

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create solution.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display a listing of solutions for a specific issue.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Issue $issue): JsonResponse
    {
        // 特定の課題に紐づく解決策を全て取得
        $solutions = $issue->solutions()->latest()->get(); // 最新の投稿から取得

        return response()->json($solutions);
    }
}
