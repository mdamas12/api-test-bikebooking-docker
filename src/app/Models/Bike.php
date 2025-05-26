<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bike extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'bikes';
    protected $fillable = [
        'company_id',
        'type_bike_id',
        'name',
        'description',
        'price',
        'status',
        'order',
        'insurance_id'
  
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category_bike(): BelongsTo
    {
        return $this->belongsTo(CategoryBike::class);
    }

    public function type_bike(): BelongsTo
    {
        return $this->belongsTo(TypeBike::class);
    }

    public function stock_items(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }

    public function price_bike_ranges(): HasMany
    {
        return $this->hasMany(PriceBikeRange::class);
    }

    public function image_bike(): HasMany
    {
        return $this->hasMany(ImageBike::class);
    }

    public function bike_accesory(): HasMany
    {
        return $this->hasMany(BikeAccesory::class);
    }

    public function insurance(): BelongsTo 
    {
        return $this->belongsTo(Insurance::class);
    }
}
