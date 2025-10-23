<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\EventStatus;

class Event extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'location', 'capacity_max',
        'categorie_id', 'status', 'registration_deadline', 'price', 'is_public', 
        'images', 'at_risk', 'risk_analysis'
    ];
    
    protected $casts = [
        'status' => EventStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_public' => 'boolean',
        'images' => 'array',
        'at_risk' => 'boolean',
    ];

    // Get all image URLs
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [asset('images/default-event.jpg')];
        }

        return array_map(function($image) {
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            return asset('storage/' . $image);
        }, $this->images);
    }

    // Get first image as featured
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            $firstImage = $this->images[0];
            if (str_starts_with($firstImage, 'http')) {
                return $firstImage;
            }
            return asset('storage/' . $firstImage);
        }
        return asset('images/default-event.jpg');
    }

    // Status helper methods
    public function isUpcoming(): bool
    {
        return $this->status === EventStatus::UPCOMING;
    }

    public function isOngoing(): bool
    {
        return $this->status === EventStatus::ONGOING;
    }

    public function isCompleted(): bool
    {
        return $this->status === EventStatus::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === EventStatus::CANCELLED;
    }

    // Date-based status calculation
    public function calculateStatusBasedOnDates(): EventStatus
    {
        $now = now();
        
        if ($this->end_date <= $now) {
            return EventStatus::COMPLETED;
        }
        
        if ($this->start_date <= $now && $this->end_date > $now) {
            return EventStatus::ONGOING;
        }
        
        return EventStatus::UPCOMING;
    }

    // Check if registration is still open
    public function isRegistrationOpen(): bool
    {
        return $this->isUpcoming() && now() <= $this->registration_deadline;
    }

    // Check if event is at risk (registration deadline approaching)
    public function shouldBeAtRisk(): bool
    {
        return $this->isUpcoming() && 
               now()->addDays(3) >= $this->registration_deadline && 
               now() < $this->registration_deadline;
    }

    // Update status based on current date
    public function updateStatusBasedOnDates(): bool
    {
        $newStatus = $this->calculateStatusBasedOnDates();
        
        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
            return true;
        }
        
        return false;
    }

    // Relationships
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    
    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }
    
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'id_evenement');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function sponsorings()
    {
        return $this->hasMany(Sponsoring::class, 'evenement_id');
    }

    public function partners()
    {
        return $this->hasManyThrough(Partner::class, Sponsoring::class, 'evenement_id', 'id', 'id', 'partenaire_id');
    }

    public function interactions()
    {
        return $this->hasMany(\App\Models\Interaction::class, 'event_id');
    }


     /**
     * Check if registration is open with countdown data
     */
    public function getRegistrationCountdownData()
    {
        $now = now();
        $deadline = $this->registration_deadline;
        
        if ($deadline <= $now) {
            return [
                'status' => 'closed',
                'message' => 'Registration closed',
                'seconds_remaining' => 0,
                'is_expired' => true,
                'days' => 0,
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0
            ];
        }
        
        $secondsRemaining = $deadline->diffInSeconds($now);
        
        return [
            'status' => 'open',
            'message' => 'Registration open',
            'seconds_remaining' => $secondsRemaining,
            'deadline' => $deadline->toISOString(),
            'days' => floor($secondsRemaining / (60 * 60 * 24)),
            'hours' => floor(($secondsRemaining % (60 * 60 * 24)) / (60 * 60)),
            'minutes' => floor(($secondsRemaining % (60 * 60)) / 60),
            'seconds' => $secondsRemaining % 60,
            'is_expired' => false,
            'is_urgent' => $secondsRemaining < (24 * 60 * 60), // Less than 24 hours
            'is_ending_soon' => $secondsRemaining < (3 * 24 * 60 * 60) // Less than 3 days
        ];
    }

    /**
     * Get registration status for styling
     */
    public function getRegistrationStatus()
    {
        $countdown = $this->getRegistrationCountdownData();
        
        if ($countdown['status'] === 'closed') {
            return 'closed';
        }
        
        if ($countdown['is_urgent']) {
            return 'urgent'; // Less than 24 hours
        }
        
        if ($countdown['is_ending_soon']) {
            return 'ending_soon'; // Less than 3 days
        }
        
        return 'open';
    }

    /**
     * Get countdown CSS class based on status
     */
    public function getCountdownClass()
    {
        $status = $this->getRegistrationStatus();
        
        switch ($status) {
            case 'urgent':
                return 'countdown-urgent';
            case 'ending_soon':
                return 'countdown-warning';
            case 'closed':
                return 'countdown-closed';
            default:
                return 'countdown-normal';
        }
    }
}