<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'registration_id',
        'file_path',
        'generated_at',
        'download_count',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    /**
     * Get the registration that owns the certificate.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}