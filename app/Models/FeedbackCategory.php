<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'active',
        'display_order'
    ];

    /**
     * Get the feedbacks for this category
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'category_id');
    }
}
