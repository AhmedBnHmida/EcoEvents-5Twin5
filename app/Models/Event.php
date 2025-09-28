<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'location', 'capacity_max', 'categorie_id', 'status', 'registration_deadline', 'price', 'is_public', 'images'
    ];
    protected $casts = [
        'status' => \App\EventStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_public' => 'boolean',
        'images' => 'array',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'id_evenement');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
    
}
