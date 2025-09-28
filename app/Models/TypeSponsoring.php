<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeSponsoring extends Model
{
    protected $fillable = [
        'nom',
    ];

    public function sponsorings()
    {
        return $this->hasMany(Sponsoring::class, 'type_sponsoring_id');
    }
}
