<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'rates';
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'value',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function rates_by_bikes(): HasMany
    {
        return $this->hasMany(FeatureByRate::class);
    }
}
