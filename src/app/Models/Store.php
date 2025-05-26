<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'stores';
    protected $fillable = [
        'company_id',
        'name' ,
        'status'
   ];

   public function company(): BelongsTo
   {
       return $this->belongsTo(Company::class);
   }

   public function stock(): BelongsTo
   {
       return $this->belongsTo(Stock::class);
   }
}
