<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'nom_societe', 'domaine_service', 'adresse', 'email', 'telephone'
    ];

    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }
}
