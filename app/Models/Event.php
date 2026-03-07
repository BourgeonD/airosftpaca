<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    protected $fillable = [
        'squad_id', 'created_by', 'title', 'description', 'rules',
        'paf_price', 'location_name', 'address', 'lat', 'lng',
        'event_date', 'max_participants', 'cover_image', 'status', 'is_private',
    ];

    protected $casts = [
        'event_date'  => 'datetime',
        'paf_price'   => 'decimal:2',
        'is_private'  => 'boolean',
    ];

    public function squad()        { return $this->belongsTo(Squad::class); }
    public function creator()      { return $this->belongsTo(User::class, 'created_by'); }
    public function participants() { return $this->belongsToMany(User::class, 'event_participants')->withTimestamps(); }
    public function photos() { return $this->hasMany(EventPhoto::class)->latest(); }
    public function joinRequests() { return $this->hasMany(EventJoinRequest::class); }
    public function invitations() { return $this->hasMany(EventInvitation::class); }

    public function isParticipating(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function isFull(): bool
    {
        if (!$this->max_participants) return false;
        return $this->participants()->count() >= $this->max_participants;
    }

    public function getIsPastAttribute(): bool
    {
        return $this->event_date->isPast();
    }
}
