<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageBike extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'image_bikes';
    protected $fillable = [
        'bike_id',
        'path',
        'is_featured',  
    ];

    public function bike(): BelongsTo
    {
        return $this->belongsTo(Bike::class);
    }
}
