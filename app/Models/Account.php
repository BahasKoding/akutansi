<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'balance'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function transfersFrom(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id')->where('type', 'transfer');
    }

    public function transfersTo(): HasMany
    {
        return $this->hasMany(Transaction::class, 'destination_account_id')->where('type', 'transfer');
    }

    public function scopeCash($query)
    {
        return $query->where('type', 'cash');
    }

    public function scopeBank($query)
    {
        return $query->where('type', 'bank');
    }
}
