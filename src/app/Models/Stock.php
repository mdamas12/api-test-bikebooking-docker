<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'stocks';
    protected $fillable = [
        'company_id',
        'name' ,
        'status'
   ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function stock_items(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }
}
