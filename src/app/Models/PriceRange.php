<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRange extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'price_ranges';
    protected $fillable = [
        'company_id',
        'min_range',
        'max_range',
        'type',
        'apply_to',
        'type_value',
        'status' 
   ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function price_bike_ranges(): HasMany
    {
        return $this->hasMany(PriceBikeRange::class);
    }

}
