<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = [
        'nom', 'type', 'fournisseur_id', 'event_id'
    ];

  
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
