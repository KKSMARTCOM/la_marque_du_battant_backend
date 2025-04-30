<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'order_no',
        'size',
        'color',
        'product_id',
        'quantity',
        'user_id',
    ];

    protected $attributes = [
        'quantity' => 1,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
