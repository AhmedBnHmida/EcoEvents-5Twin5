<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsoring extends Model
{
    protected $fillable = [
        'montant',
        'type_sponsoring_id',
        'date',
        'partenaire_id',
        'evenement_id',
    ];

    protected $primaryKey = 'id';

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partenaire_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'evenement_id');
    }

    public function typeSponsoring()
    {
        return $this->belongsTo(TypeSponsoring::class, 'type_sponsoring_id');
    }
}
