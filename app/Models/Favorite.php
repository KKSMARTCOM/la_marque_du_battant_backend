<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'user_id',
        'product_id',
        'size_selected'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
