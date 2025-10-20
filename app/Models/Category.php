<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'type'
    ];
    protected $casts = [
        'type' => \App\TypeCategorie::class,
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'categorie_id');
    }
}
