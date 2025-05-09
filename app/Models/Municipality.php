<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'source',
    ];

    /**
     * Get the open data records for the municipality.
     */
    public function openData(): HasMany
    {
        return $this->hasMany(OpenData::class);
    }

    // /**
    //  * Get the datasets for the municipality.
    //  */
    // public function datasets(): HasMany
    // {
    //     return $this->hasMany(Dataset::class);
    // }
}
