<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'user_id',
        'order_no',
        'status',
        'price',
        'transaction_id'
    ];

    protected $dates = ['created_at'];

    protected $attributes = [
        'status' => false,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'order_no', 'order_no');
    }
}
