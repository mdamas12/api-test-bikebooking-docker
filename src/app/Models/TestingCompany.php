<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestingCompany extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'testing_companies';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
