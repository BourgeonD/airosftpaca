<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Squad extends Model
{
    protected $fillable = [
        'name', 'tag', 'slug', 'description', 'history', 'logo', 'banner',
        'city', 'website', 'facebook', 'instagram',
        'leader_id', 'is_recruiting', 'min_age',
    ];

    protected $casts = ['is_recruiting' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($squad) {
            if (!$squad->slug) {
                // Slug basé sur le tag si présent, sinon le nom
                $squad->slug = Str::slug($squad->tag ?? $squad->name);
            }
        });
    }

    // ── Accessor : affichage complet "TAG - Nom complet" ou juste le nom ──────
    public function getDisplayNameAttribute(): string
    {
        if ($this->tag && $this->name) {
            return strtoupper($this->tag) . ' - ' . $this->name;
        }
        return $this->name;
    }

    // ── Accessor : affichage court (tag ou nom) ────────────────────────────────
    public function getShortNameAttribute(): string
    {
        return $this->tag ? strtoupper($this->tag) : $this->name;
    }

    public function leader()        { return $this->belongsTo(User::class, 'leader_id'); }
    public function members()       { return $this->hasMany(SquadMember::class); }
    public function events()        { return $this->hasMany(Event::class); }
    public function pendingRequests() { return $this->hasMany(SquadJoinRequest::class)->where('status', 'pending'); }
    public function joinRequests()  { return $this->hasMany(SquadJoinRequest::class); }
    public function invitations()  { return $this->hasMany(SquadInvitation::class); }
    public function photos()        { return $this->hasMany(EventPhoto::class)->with('event')->latest(); }

    public function upcomingEvents()
    {
        return $this->hasMany(Event::class)
            ->whereIn('status', ['published', 'closed'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date');
    }

    public function pastEvents()
    {
        return $this->hasMany(Event::class)
            ->whereIn('status', ['published', 'closed', 'completed'])
            ->where('event_date', '<', now())
            ->orderBy('event_date', 'desc');
    }
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
