<?php

namespace App;

enum TypeSponsoring: string
{
    case ARGENT = 'argent';
    case MATERIEL = 'materiel';
    case LOGISTIQUE = 'logistique';
    case AUTRE = 'autre';

    public function label(): string
    {
        return match($this) {
            self::ARGENT => 'Argent',
            self::MATERIEL => 'Matériel',
            self::LOGISTIQUE => 'Logistique',
            self::AUTRE => 'Autre',
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label()
        ], self::cases());
    }
}
