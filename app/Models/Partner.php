<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nom',
        'type',
        'contact',
        'email',
        'telephone',
        'logo',
    ];

    public function sponsorings()
    {
        return $this->hasMany(Sponsoring::class, 'partenaire_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor to get contact name (from user or manual entry)
    public function getContactNameAttribute()
    {
        return $this->user ? $this->user->name : $this->contact;
    }

    // Accessor to get email (from user or manual entry)
    public function getContactEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->email;
    }

    // Accessor to get logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        // Default logo if none
        return asset('assets/img/default-partner-logo.png');
    }
}
