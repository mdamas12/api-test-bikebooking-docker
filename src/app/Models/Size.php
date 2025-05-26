<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Size extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'sizes';
    protected $fillable = [
        'company_id',
        'name' 
   ];

   public function stock_items(): HasMany
   {
       return $this->hasMany(StockItem::class);
   }
}
