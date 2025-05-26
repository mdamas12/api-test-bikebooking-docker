<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Season extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'seasons';
    protected $fillable = [
        'company_id',
        'name'  
   ];

   public function company(): BelongsTo
   {
       return $this->belongsTo(Company::class);
   }

   public function season_ranges(): HasMany
   {
       return $this->hasMany(SeasonRange::class);
   }


}
