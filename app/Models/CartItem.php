<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    // Table associated with the model
    protected $table = 'cart_items';

    // Primary key for the model
    protected $primaryKey = 'id';

    // Attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Inventory::class);
    }
}
