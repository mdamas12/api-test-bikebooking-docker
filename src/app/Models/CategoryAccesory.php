<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAccesory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'category_accesories';
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'status',
        'path',
        'multiselect'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accesories(): HasMany
    {
        return $this->hasMany(Accesory::class);
    }
}
