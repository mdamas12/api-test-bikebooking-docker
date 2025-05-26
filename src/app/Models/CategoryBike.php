<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryBike extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'category_bikes';
    protected $fillable = [
        'name',
    ];

    public function type_bikes(): HasMany
    {
        return $this->hasMany(TypeBike::class);
    }
}
