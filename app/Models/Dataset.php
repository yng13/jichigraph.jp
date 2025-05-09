<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dataset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'source',
        'publication_date',
        'update_frequency',
        'data_source_type',
        'data_source_url',
    ];

    /**
     * Get the open data records for the dataset.
     */
    public function openData(): HasMany
    {
        return $this->hasMany(OpenData::class);
    }

    // /**
    //  * Get the municipality that owns the dataset.
    //  */
    // public function municipality(): BelongsTo
    // {
    //     return $this->belongsTo(Municipality::class);
    // }
}
