<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BikeAccesory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'bike_accesories';
    protected $fillable = [
        'bike_id',
        'accesory_id',  
    ];

    public function accesory(): BelongsTo
    {
        return $this->belongsTo(Accesory::class);
    }

    public function bike(): BelongsTo
    {
        return $this->belongsTo(Bike::class);
    }
}
