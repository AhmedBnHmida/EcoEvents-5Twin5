<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeRessource extends Model
{
    // Types de ressources disponibles
    public static function allTypes(): array
    {
        return [
            'Décoration',
            'Nourriture',
            'Matériel',
            'Transport',
            'Électronique',
            'Hygiène',
            'Communication',
            'Papeterie',
            'Énergie',
            'Nettoyage',
            'Sécurité',
            'Autre',
        ];
    }
}
