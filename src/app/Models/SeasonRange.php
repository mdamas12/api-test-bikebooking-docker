<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonRange extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'season_ranges';
    protected $fillable = [
        'company_id',
        'season_id',
        'ini_season',
        'end_season',
        'value',
        'status'
   ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function season_bikes(): HasMany
    {
        return $this->hasMany(SeasonBike::class);
    }


}
