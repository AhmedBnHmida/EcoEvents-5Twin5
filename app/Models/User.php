<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'password', 'role'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'id_participant');
    }

    // Méthodes pratiques pour vérifier les rôles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isFournisseur()
    {
        return $this->role === 'fournisseur';
    }

    public function isParticipant()
    {
        return $this->role === 'participant';
    }

    public function isOrganisateur()
    {
        return $this->role === 'organisateur';
    }

    public function isUtilisateur()
    {
        return $this->role === 'utilisateur';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
