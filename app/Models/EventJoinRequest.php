<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventJoinRequest extends Model
{
    protected $fillable = ['event_id', 'user_id', 'message', 'status', 'reviewed_at'];
    protected $casts    = ['reviewed_at' => 'datetime'];

    public function event() { return $this->belongsTo(Event::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
