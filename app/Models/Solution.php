<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'user_id',
        'content',
        'status',
        'votes',
    ];

    protected $casts = [
        'votes' => 'integer',
    ];

    /**
     * Solutionが関連するIssueを取得
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Solutionを提案したユーザーを取得
     */
    public function user()
    {
        // ユーザー認証機能の実装後に、Userモデルと関連付けます
        // 現時点ではApp\Models\Userが存在しない可能性があるのでコメントアウト
        // return $this->belongsTo(User::class);
    }
}
