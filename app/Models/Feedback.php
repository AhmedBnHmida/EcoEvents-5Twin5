<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id_feedback';
    protected $fillable = [
        'id_evenement',
        'id_participant',
        'note',
        'commentaire',
        'date_feedback',
    ];

    protected $casts = [
        'date_feedback' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_evenement');
    }

    public function participant()
    {
        return $this->belongsTo(User::class, 'id_participant');
    }
}
