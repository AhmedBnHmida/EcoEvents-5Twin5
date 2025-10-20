<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\TypeSponsoring;

class Sponsoring extends Model
{
    use HasFactory;
    protected $fillable = [
        'montant',
        'type_sponsoring',
        'date',
        'partenaire_id',
        'evenement_id',
    ];

    protected $casts = [
        'type_sponsoring' => TypeSponsoring::class,
        'date' => 'date',
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
}
