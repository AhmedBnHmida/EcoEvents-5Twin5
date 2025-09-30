<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'nom',
        'type',
        'contact',
        'email',
        'telephone',
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
}
