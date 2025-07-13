<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointOfInterest extends Model
{
    use HasFactory;

    // テーブル名を指定（デフォルトで複数形になるが、明示的に指定）
    protected $table = 'points_of_interest';

    // マスアサインメントを許可するカラムを指定
    protected $fillable = [
        'municipality_code',
        'municipality_name',
        'type',
        'name',
        'name_kana',
        'name_english',
        'address',
        'prefecture',
        'city',
        'town',
        'block_number',
        'building_name',
        'latitude',
        'longitude',
        'phone_number',
        'contact_email',
        'url',
        'remarks',
        'details', // JSONカラムも忘れずに含める
    ];

    // JSONカラムをキャストして自動で配列/オブジェクトに変換
    protected $casts = [
        'details' => 'array',
        'child_friendly' => 'boolean', // 必要に応じてマイグレーションで定義したbooleanカラムも追加
        'external_use_forbidden' => 'boolean', // 必要に応じてマイグレーションで定義したbooleanカラムも追加
        'latitude' => 'float',
        'longitude' => 'float',
        'height_value' => 'float',
    ];
}
