<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'stock_items';
    protected $fillable = [
        'stock_id',
        'bike_id' ,
        'size_id',
        'code',
        'arrival',
        'output',
        'arrival',
        'dimension'
   ];

   public function stock(): BelongsTo
   {
       return $this->belongsTo(Stock::class);
   }

   public function size(): BelongsTo
   {
       return $this->belongsTo(Size::class);
   }

   public function bike(): BelongsTo
   {
       return $this->belongsTo(Bike::class);
   }

}
