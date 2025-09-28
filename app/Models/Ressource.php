<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = [
        'nom', 'type', 'fournisseur_id', 'event_id'
    ];
    protected $casts = [
        'type' => \App\TypeRessource::class,
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
