<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalEvaluation extends Model
{
    protected $table = 'global_evaluations';
    protected $fillable = [
        'id_evenement',
        'moyenne_notes',
        'nb_feedbacks',
        'taux_satisfaction',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_evenement');
    }
}
