<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AuditLog model for Kolydart Auditable trait compatibility
 * 
 * This extends/aliases the existing Activitylog model to work with 
 * the Kolydart\Laravel\App\Traits\Auditable trait
 */
class AuditLog extends Activitylog
{
    use HasFactory;

    protected $table = 'activity_log';

    protected $fillable = [
        'description',
        'subject_id', 
        'subject_type',
        'user_id',
        'properties',
        'host'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Override the parent's fillable to include the fields that Kolydart expects
     */
    public function __construct(array $attributes = [])
    {
        // Map Kolydart expected fields to Spatie activity log fields
        if (isset($attributes['user_id'])) {
            $attributes['causer_id'] = $attributes['user_id'];
            $attributes['causer_type'] = 'App\\User';
        }

        parent::__construct($attributes);
    }

    /**
     * Set user_id attribute (maps to causer_id)
     */
    public function setUserIdAttribute($input)
    {
        $this->attributes['causer_id'] = $input ? $input : null;
        $this->attributes['causer_type'] = $input ? 'App\\User' : null;
    }

    /**
     * Get user_id attribute (maps from causer_id)
     */
    public function getUserIdAttribute()
    {
        return $this->attributes['causer_id'] ?? null;
    }

    /**
     * Set host attribute for IP tracking
     */
    public function setHostAttribute($input)
    {
        // We can store this in properties since activity_log doesn't have host field
        $properties = $this->properties ?? [];
        $properties['host'] = $input;
        $this->attributes['properties'] = json_encode($properties);
    }
}