<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureByRate extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'feature_by_rates';
    protected $fillable = [
        'feature_rate_id',
        'rate_id',
    ];

    public function feature_rate(): BelongsTo
    {
        return $this->belongsTo(FeatureRate::class);
    }

    public function rate(): BelongsTo
    {
        return $this->hasMany(Rate::class);
    }
}
