<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'companies';
    protected $fillable = [
        'contact_name',
        'email',
        'company_name',
        'phone',
        'fiscal_name',
        'cif',
        'address',
        'country',
        'website_url',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bikes(): HasMany
    {
        return $this->hasMany(Bike::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function price_range(): HasMany
    {
        return $this->hasMany(PriceRange::class);
    }

    public function season(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function season_range(): HasMany
    {
        return $this->hasMany(SeasonRange::class);
    }

    public function testing_company(): BelongsTo
    {
        return $this->belongsTo(TestingCompany::class);
    }

    public function accesories(): HasMany
    {
        return $this->hasMany(Accesory::class);
    }

    public function category_accesory(): HasMany
    {
        return $this->hasMany(CategoryAccesory::class);
    }

}

