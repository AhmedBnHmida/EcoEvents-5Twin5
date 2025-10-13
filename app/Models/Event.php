<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'location', 'capacity_max','categorie_id', 'status', 'registration_deadline', 'price', 'is_public', 'images'
    ];
    protected $casts = [
        'status' => \App\EventStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_public' => 'boolean',
        'images' => 'array',
    ];


    // Get all image URLs
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [asset('images/default-event.jpg')];
        }

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    // Get first image as featured
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }
        return asset('images/default-event.jpg');
    }


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

    public function sponsorings()
    {
        return $this->hasMany(Sponsoring::class, 'evenement_id');
    }

    public function partners()
    {
        return $this->hasManyThrough(Partner::class, Sponsoring::class, 'evenement_id', 'id', 'id', 'partenaire_id');
    }

    


    public function interactions()
{
    return $this->hasMany(\App\Models\Interaction::class, 'event_id');
}


}
