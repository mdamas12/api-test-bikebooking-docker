<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TypeBike extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'type_bikes';
    protected $fillable = [
        'name',
        'description' ,
        'status'
   ];

   /* public function category_bike(): BelongsTo
    {
        return $this->belongsTo(CategoryBike::class);
    }
   */
    public function bikes(): HasMany
    {
        return $this->hasMany(Bike::class);
    }
}
  
