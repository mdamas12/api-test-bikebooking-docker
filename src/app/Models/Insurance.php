<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insurance extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'insurances';
    protected $fillable = [
        'company_id',
        'name',
        'description',  
        'price',  
        'status',  
    ];

    public function bike(): HasMany
    {
        return $this->hasMany(Bike::class);
    }
}
