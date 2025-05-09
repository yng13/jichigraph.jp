<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenData extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'municipality_id',
        'dataset_id',
        'record_identifier',
        'title',
        'data_type',
        'latitude',
        'longitude',
        'data',
    ];

    /**
     * Get the municipality that owns the OpenData.
     */
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Get the dataset that owns the OpenData.
     */
    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
}
