<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonBike extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = 'season_bikes';
    protected $fillable = [
        'bike_id',
        'season_range_id',
        'value',
        'status'
   ];

    public function bike(): BelongsTo
    {
        return $this->belongsTo(bike::class);
    }

    public function season_range(): BelongsTo
    {
        return $this->belongsTo(SeasonRange::class);
    }

}
