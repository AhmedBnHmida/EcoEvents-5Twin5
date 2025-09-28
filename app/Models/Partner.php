<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
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
}
