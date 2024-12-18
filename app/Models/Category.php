<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    protected $attributes = [
        'type' => 'income' // Default type is always income
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
