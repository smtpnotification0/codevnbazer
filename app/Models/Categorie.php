<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $fillable = [
        'title',
        'slot',
        'status',
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
    public function products(): HasMany
    {
        return $this->hasMany(Veriabals::class);
    }
}
