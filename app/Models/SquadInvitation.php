<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SquadInvitation extends Model
{
    protected $fillable = [
        'squad_id', 'user_id', 'event_id',
        'status', 'token', 'expires_at',
    ];

    protected $casts = ['expires_at' => 'datetime'];

    public function squad() { return $this->belongsTo(Squad::class); }
    public function user()  { return $this->belongsTo(User::class); }
    public function event() { return $this->belongsTo(Event::class); }
}
