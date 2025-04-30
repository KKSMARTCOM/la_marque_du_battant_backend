<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'country',
        'address',
        'startDate',
        'endDate'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
