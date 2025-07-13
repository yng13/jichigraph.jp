<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'municipality_code',
        'latitude',
        'longitude',
        'image_url',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Issueが関連するユーザーを取得
     */
    public function user()
    {
        // return $this->belongsTo(User::class);
    }

    /**
     * この課題に紐づく解決策を取得
     */
    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }
}
