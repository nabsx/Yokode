<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'price_coins',
        'effects',
        'is_available',
    ];

    protected $casts = [
        'effects' => 'array',
        'is_available' => 'boolean',
    ];

    public function inventories()
    {
        return $this->hasMany(UserInventory::class);
    }
}