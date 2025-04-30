<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, GeneratesUuid;

    // Attributs qui peuvent être assignés en masse
    protected $fillable = [
        'name',
        'description',
        'price',
        'size',
        'color',
        'quantity',
        'main_image',
        'additional_images',
        'stock',
        'collection_id',
        'category_id'
    ];

    // Cast JSON fields to array
    protected $casts = [
        'size' => 'array',
    ];

    // Définir la relation avec la table catégories
    public function categorie()
    {
        return $this->belongsTo(Category::class);
    }

    public function favoredByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }


    // Définir la relation avec la table utilisateurs pour les mises à jour
    /* public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    } */
}
