<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class PriceBikeRange extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'price_bikes';
    protected $fillable = [
        'bike_id',
        'price_range_id',
        'value',
   ];

    public function bike(): BelongsTo
    {
        return $this->belongsTo(bike::class);
    }

    public function price_range(): BelongsTo
    {
        return $this->belongsTo(PriceRange::class);
    }
}
