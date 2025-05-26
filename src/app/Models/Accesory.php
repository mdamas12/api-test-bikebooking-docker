<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accesory extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = 'accesories';
    protected $fillable = [
        'company_id',
        'category_accesory_id',
        'name',
        'path',
        'order',
        'description',
        'status',
        'quantity',
        'price_day',
        'price_booking',
        'is_price_booking'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category_accesory(): BelongsTo
    {
        return $this->belongsTo(CategoryAccesory::class);
    }

    public function bike_accesory(): HasMany
    {
        return $this->hasMany(BikeAccesory::class);
    }

 
}
