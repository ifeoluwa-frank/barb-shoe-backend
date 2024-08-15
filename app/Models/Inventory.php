<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'color',
        'size',
        'image',
    ];

    protected $casts = [
        'size' => 'array',
        'color' => 'array'
    ];

    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

}
