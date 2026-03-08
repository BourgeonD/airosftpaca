<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
	'birthdate' => 'date',
    ];

    // Dans $fillable, ajouter :
// 'trust_score', 'pseudo', 'birthdate', 'game_style', 'equipment', 'games_played'

protected $fillable = [
    'name', 'email', 'password', 'role', 'trust_score',
    'pseudo', 'bio', 'avatar', 'location', 'birthdate',
    'game_style', 'equipment', 'games_played',
];

// ── Trust Factor ─────────────────────────────────────────────────────────────

public function reportsReceived()
{
    return $this->hasMany(UserReport::class, 'reported_id');
}

public function reportsSent()
{
    return $this->hasMany(UserReport::class, 'reporter_id');
}

public function recalculateTrust(): void
{
    $positives = $this->reportsReceived()->where('type', 'positive')->count();
    $negatives = $this->reportsReceived()->where('type', 'negative')->count();
    $total     = $positives + $negatives;

    // Pas assez de données : score de base
    if ($total < 3) {
        $score = 3.0;
    } else {
        // Positifs pèsent 1 point, négatifs pèsent 0.4 point
        // mais les négatifs sont cumulatifs avec plus d'impact en masse
        $impact = ($positives * 1.0) - ($negatives * 0.4);

        // Malus supplémentaire si beaucoup de négatifs d'un coup
        if ($negatives > 10) {
            $impact -= ($negatives - 10) * 0.2;
        }

        $score = 3.0 + ($impact / max($total, 5)) * 2;
        $score = round($score * 2) / 2; // Arrondi au 0.5 le plus proche
    }

    // Borner entre 1 et 5
    $score = max(1.0, min(5.0, $score));

    $this->update(['trust_score' => $score]);
}

public function getTrustLabelAttribute(): string
{
    return match(true) {
        $this->trust_score >= 4.5 => 'Excellent',
        $this->trust_score >= 3.5 => 'Fiable',
        $this->trust_score >= 2.5 => 'Correct',
        $this->trust_score >= 1.5 => 'Prudence',
        default                   => 'Signalé',
    };
}

public function getTrustColorAttribute(): string
{
    return match(true) {
        $this->trust_score >= 4.5 => 'text-emerald-400',
        $this->trust_score >= 3.5 => 'text-green-400',
        $this->trust_score >= 2.5 => 'text-yellow-400',
        $this->trust_score >= 1.5 => 'text-orange-400',
        default                   => 'text-red-400',
    };
}

public function getTrustBgAttribute(): string
{
    return match(true) {
        $this->trust_score >= 4.5 => 'bg-emerald-900/40 border-emerald-700/50',
        $this->trust_score >= 3.5 => 'bg-green-900/40 border-green-700/50',
        $this->trust_score >= 2.5 => 'bg-yellow-900/40 border-yellow-700/50',
        $this->trust_score >= 1.5 => 'bg-orange-900/40 border-orange-700/50',
        default                   => 'bg-red-900/40 border-red-700/50',
    };
}

public function getDisplayNameAttribute(): string
{
    return $this->pseudo ?? $this->name;
}

    // ── Helpers de rôle ──────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSquadLeader(): bool
    {
        return $this->role === 'squad_leader' || $this->role === 'admin';
    }

    public function hasSquad(): bool
    {
        return $this->squadMembership()->exists();
    }

    // ── Relations ────────────────────────────────────────────────────────────

    public function ledSquad()
    {
        return $this->hasOne(Squad::class, 'leader_id');
    }

    public function squadMembership()
    {
        return $this->hasOne(SquadMember::class);
    }

    public function squad()
    {
        return $this->hasOneThrough(Squad::class, SquadMember::class, 'user_id', 'id', 'id', 'squad_id');
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function forumThreads()
    {
        return $this->hasMany(ForumThread::class);
    }

    public function participatingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participants')
                    ->withTimestamps();
    }

    public function roleRequests()
    {
        return $this->hasMany(RoleRequest::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
