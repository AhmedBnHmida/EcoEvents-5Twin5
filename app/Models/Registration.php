<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'ticket_code', 'qr_code_path', 'status', 'registered_at', 'attended_at'
    ];
    protected $casts = [
        'status' => \App\RegistrationStatus::class,
        'registered_at' => 'datetime',
        'attended_at' => 'datetime',
    ];
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
